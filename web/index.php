<?php

require_once __DIR__."/../bootstrap.php";

session_start();

$app = new Silex\Application();

$app["debug"] = $config["debug"];
$app["config"] = $config;

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
  'locale_fallbacks' => array('en'),
));
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    "twig.path" => __DIR__."/views",
));

$updateAppointmentSlots = function() use($config) {
	$ics_url = sprintf("https://www.google.com/calendar/ical/%s/public/basic.ics", urlencode($config["google"]["calendar_id"]));
	$calendar = new MattLeff\Calendar\ICalendar($ics_url);
	
	$formstack_config = $config["formstack"];
	MattLeff\Formstack\API::setAccessToken($formstack_config["access_token"]);
	$form = MattLeff\Formstack\API::get()->getForm($formstack_config["form_id"]);
	
	$calendar_updater = new MattLeff\FormstackCalendarUpdater($calendar, $config["calendar"]);
	$calendar_updater->updateForm($form);
};

$app->get("/", function() use($app, $config, $updateAppointmentSlots) {
	$updateAppointmentSlots();
	return $app["twig"]->render("index.twig", array(
		"calendar_id" => $config["google"]["calendar_id"],
		"form_id" => $config["formstack"]["form_id"],
	));
});

$app->get('/update', function() use($updateAppointmentSlots) {
	$updateAppointmentSlots();
	
	return json_encode(array("success" => true));
});

$app->run();
