<?php

namespace Qsomazzi\Particle\Command;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Qsomazzi\Particle\Admin\AbstractAdmin;
use Qsomazzi\Particle\Admin\AdminInterface;
use Qsomazzi\Particle\Attributes\Admin;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\UseStatementGenerator;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AutoconfigureTag('make.command')]
final class MakeAdmin extends AbstractMaker
{
    public function __construct(
        #[Autowire(service: 'maker.doctrine_helper')]
        private DoctrineHelper $entityHelper
    ) {
    }

    public static function getCommandName(): string
    {
        return 'make:admin';
    }

    public static function getCommandDescription(): string
    {
        return 'Create a new admin class [Particle]';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->addArgument('name', InputArgument::OPTIONAL, \sprintf('The name of the admin class (e.g. <fg=yellow>%sAdmin</>)', Str::asClassName(Str::getRandomTerm())))
            ->addArgument('entity-class', InputArgument::OPTIONAL, 'The name of Entity that the new admin will use')
            ->addArgument('form-class', InputArgument::OPTIONAL, 'The name of Form that the new admin will use')
            ->setHelp(file_get_contents(\sprintf('%s/config/help/%s', \dirname(__DIR__, 2), 'MakeAdmin.txt')))
        ;

        $inputConfig->setArgumentAsNonInteractive('entity-class');
        $inputConfig->setArgumentAsNonInteractive('form-class');
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
        if (null === $input->getArgument('entity-class')) {
            $argument = $command->getDefinition()->getArgument('entity-class');

            $entities = $this->entityHelper->getEntitiesForAutocomplete();

            $question = new Question($argument->getDescription());
            $question->setAutocompleterValues($entities);
            $question->setMaxAttempts(3);

            $input->setArgument('entity-class', $io->askQuestion($question));
        }

        if (null === $input->getArgument('form-class')) {
            $argument = $command->getDefinition()->getArgument('form-class');

            $question = new Question($argument->getDescription());
            $question->setValidator(function ($answer) {
                Validator::validateClassName($answer);
                return $answer;
            });

            $input->setArgument('form-class', $io->askQuestion($question));
        }
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $adminClassNameDetails = $generator->createClassNameDetails(
            $input->getArgument('name'),
            'Admin\\',
            'Admin'
        );

        $entityClass = $input->getArgument('entity-class');
        $formClass = $input->getArgument('form-class');

        $useStatements = new UseStatementGenerator([
            AbstractAdmin::class,
            AdminInterface::class,
            Admin::class,
        ]);

        $generator->generateClass(
            $adminClassNameDetails->getFullName(),
            __DIR__.'/../../templates/Maker/Admin.tpl.php',
            [
                'use_statements' => $useStatements,
                'entity_class' => $entityClass,
                'form_class' => $formClass,
            ]
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);

        $io->text([
            'Next: Update fields in the admin and start using it.',
        ]);
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
        $dependencies->addClassDependency(
            DoctrineBundle::class,
            'orm',
            false
        );
    }
}