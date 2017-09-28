<?php

/**
 * @error https://www.ja.advertisercommunity.com/t5/%E3%81%9D%E3%81%AE%E4%BB%96-Google-%E3%82%A2%E3%83%8A%E3%83%AA%E3%83%86%E3%82%A3%E3%82%AF%E3%82%B9/403-User-Rate-Limit-Exceeded/td-p/17590#
 */

// Load the Google API PHP Client Library.
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

const START_DATE = '2017-09-24';

$i = 0;
$today = date('Y-m-d', strtotime('-1 hour', time()));
$start_date = date(("Y-m-d"), strtotime(START_DATE));
$end_date = $start_date;

$analytics = initializeAnalytics();

while($end_date != $today){
  $response = getReport($analytics, $start_date, $end_date);
  printResults($response);
  $i++;
  $start_date = date(("Y-m-d"), strtotime("+". $i. " day",strtotime(START_DATE)));
  $end_date = $start_date;
}

function initializeAnalytics()
{
  // Creates and returns the Analytics Reporting service object.

  // Use the developers console and download your service account
  // credentials in JSON format. Place them in this directory or
  // change the key file location if necessary.
  $KEY_FILE_LOCATION = __DIR__ . '/'. $_ENV['KEY_FILE'];

  // Create and configure a new client object.
  $client = new Google_Client();
  $client->setApplicationName("Hello Analytics Reporting");
  $client->setAuthConfig($KEY_FILE_LOCATION);
  $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
  $analytics = new Google_Service_AnalyticsReporting($client);

  return $analytics;
}

function getReport($analytics, $start_date, $end_date) {

  // Replace with your view ID, for example XXXX.
  $VIEW_ID = $_ENV['VIEW_ID'];

  // Create the DateRange object.
  $dateRange = new Google_Service_AnalyticsReporting_DateRange();
  $dateRange->setStartDate($start_date);
  $dateRange->setEndDate($end_date);

  // Create the Metrics object.
  $sessions = new Google_Service_AnalyticsReporting_Metric();
  $sessions->setExpression("ga:pageviews");
  $sessions->setAlias("sessions");

  $dimention1 = new \Google_Service_AnalyticsReporting_Dimension();
  $dimention1->setName( 'ga:channelGrouping' );

  $dimention2 = new \Google_Service_AnalyticsReporting_Dimension();
  $dimention2->setName( 'ga:deviceCategory' );

  $dimention3 = new \Google_Service_AnalyticsReporting_Dimension();
  $dimention3->setName( 'ga:date' );

  // Create the Filter object.
  $filter = new \Google_Service_AnalyticsReporting_DimensionFilter();
  $filter->setDimensionName( 'ga:pagePath' );
  $filter->setOperator( 'BEGINS_WITH' );
  $filter->setExpressions( [ '/get-marry-with-vietnamese-in-japan-1/' ] );

  $filters = new \Google_Service_AnalyticsReporting_DimensionFilterClause();
  $filters->setFilters([$filter]);

  // Create the ReportRequest object.
  $request = new Google_Service_AnalyticsReporting_ReportRequest();
  $request->setViewId($VIEW_ID);
  $request->setDateRanges($dateRange);
  $request->setMetrics(array($sessions));
  $request->setDimensions( [ $dimention1, $dimention2, $dimention3 ] );
  $request->setDimensionFilterClauses( $filters );

  $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
  $body->setReportRequests( array( $request) );
  return $analytics->reports->batchGet( $body );
}

function printResults($reports) {
  for ( $reportIndex = 0; $reportIndex < count( $reports ); $reportIndex++ ) {
    $report = $reports[ $reportIndex ];
    $header = $report->getColumnHeader();
    $dimensionHeaders = $header->getDimensions();
    $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
    $rows = $report->getData()->getRows();

    for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
      $row = $rows[ $rowIndex ];
      $dimensions = $row->getDimensions();
      $metrics = $row->getMetrics();

	  $results = array();

      for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) {
        $results[] = $dimensions[$i];
      }

      for ($j = 0; $j < count( $metricHeaders ) && $j < count( $metrics ); $j++) {
        $entry = $metricHeaders[$j];
        $values = $metrics[$j];
        for ( $valueIndex = 0; $valueIndex < count( $values->getValues() ); $valueIndex++ ) {
          $value = $values->getValues()[ $valueIndex ];
          $results[] = $value;
        }
      }
	  setCsv($results);
	}
  }
}

function setCsv(array $data){
	$f = fopen("data.csv", "a");
	if ( $f ) {
		fputcsv($f, $data);
	}
	fclose($f);
}
