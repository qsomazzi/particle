<?= "<?php\n" ?>

namespace <?= $namespace ?>;

<?= $use_statements; ?>

#[Admin(
entityClass: <?= $entity_class; ?>::class,
formClass: <?= $form_class; ?>::class,
// identifier: 'id', // You can change the default identifier (used in routing mostly)
// prefix: '', // You can set a specific prefix for this admin
// maxPerPage: 10, // You can change the default max rows per page (used in Index page)
// defaultSortColumn: 'id', // You can change the default sort column (used in Index page)
// defaultSortDirection: 'desc', // You can change the default sort direction (used in Index page)
// defaultSearchColumns: [], // You can define some default search columns (used in Index page and global search)
// operations: [ // By default Index, Create, Update and Delete are enabled. You can add as many Custom operations as you want
// Index::class,
// Read::class,
// Create::class,
// Update::class,
// Delete::class,
// [
// 'class'      => Custom::class,
// 'name'       => 'export',
// 'path'       => '/export', // The path is guessed from the name if not provided
// 'controller' => ExportAction::class,
// 'icon'       => 'ti ti-download',
// 'displayInRowActions' => false, // If true, on index view, it will be displayed in each row. If not set, it will be guessed from the path
// 'displayInPageActions' => true, // If true, on index view, it will be displayed in the top right corner. If not set, it will be guessed from the path
// ],
// [
// 'class'      => Custom::class,
// 'name'       => 'duplicate',
// 'path'       => '/{slug}/duplicate',
// 'controller' => IndexAction::class,
// 'icon'       => 'ti ti-copy',
// 'displayInRowActions' => true,
// 'displayInPageActions' => false,
// ],
// ],
// domain: 'spreaders', // Guessed from the entity class name if not provided
// singularLabel: 'event', // Guessed from the entity class name if not provided
// pluralLabel: 'events', // Guessed from the entity class name if not provided
// shortName: 'event', // Guessed from the entity class name if not provided
)]
class <?= $class_name ?> extends AbstractAdmin implements AdminInterface
{
/**
* Define the fields
*/
public function configureListFields(): array
{
return [
'id',

// You can define your custom fields here

'_actions',
];
}
}
