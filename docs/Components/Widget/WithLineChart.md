# WithLineChart

## Requirements

- This component uses [apexcharts](https://apexcharts.com/), install this dependency on your project before using it.

## Usage

```twig
    <twig:widget:withLineChart
        title="Widget withLineChart"
        value="12"
        :labels="['2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24']"
        :data="[{'name':'Liste 1', 'data': [37, 35, 44, 28]}]"
    />
    
    <twig:widget:withLineChart
        title="Widget withLineChart"
        value="12"
        trending="23"
        trendingUnit="%"
        :labels="['2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24']"
        :data="[{'name':'Liste 1', 'data': [37, 35, 44, 28]}]"
    />
```
