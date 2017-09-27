Analytics-php
====

This app access analytics and get data

## REQUIREMENT
* [composer](https://getcomposer.org/)

## SET UP
```
$ composer install
```

## SET ENV
```
KEY_FILE="your-service-account-credential-json-file"
VIEW_ID="your-view-id"
```

## START
```
$ php index.php
```

## Reference
* [Reporting API v4](https://developers.google.com/analytics/devguides/reporting/core/v4/quickstart/service-php)
* [google/google-api-php-client](https://github.com/google/google-api-php-client)
* [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)
* [Dimensions & Metrics Explorer](https://developers.google.com/analytics/devguides/reporting/core/dimsmets)
* [メソッド: reports.batchGet](https://developers.google.com/analytics/devguides/reporting/core/v4/rest/v4/reports/batchGet?hl=ja)
* [PHPを使って GoogleAnalytics にある特定ページの情報を受け取る](https://qiita.com/a_yasui/items/9c6fff66aa92a54c8298)
* [Google Analyticsのランディングページのデータを取得する](http://masalib.hatenablog.com/entry/2016/10/31/220155#複数のカラムMetric-に対応)
