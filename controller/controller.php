<?php
/**
 * Copyright (c) 2014 Georg Ehrke <oc.list@georgehrke.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */
namespace OCA\Calendar\Controller;

use \OCP\AppFramework\IAppContainer;
use \OCP\IRequest;

use \OCA\Calendar\BusinessLayer\BusinessLayer;
use \OCA\Calendar\BusinessLayer\CalendarBusinessLayer;
use \OCA\Calendar\BusinessLayer\ObjectBusinessLayer;

use \DateTime;

abstract class Controller extends \OCP\AppFramework\Controller {

	/**
	 * calendar business layer
	 * @var \OCA\Calendar\BusinessLayer\CalendarBusinessLayer
	 */
	protected $cbl;


	/**
	 * object business layer
	 * @var \OCA\Calendar\BusinessLayer\ObjectBusinessLayer
	 */
	protected $obl;


	/**
	 * core api
	 * @var \OCP\AppFramework\IApi
	 */
	protected $api;


	/**
	 * constructor
	 * @param IAppContainer $app interface to the app
	 * @param IRequest $request an instance of the request
	 * @param CalendarBusinessLayer $calendarBusinessLayer
	 * @param ObjectBusinessLayer $objectBusinessLayer
	 */
	public function __construct(IAppContainer $app, IRequest $request,
								$calendarBusinessLayer=null, $objectBusinessLayer=null){

		parent::__construct($app, $request);

		$this->app = $app;
		$this->api = $app->getCoreApi();

		if ($calendarBusinessLayer instanceof CalendarBusinessLayer) {
			$this->cbl = $calendarBusinessLayer;
		}
		if ($objectBusinessLayer instanceof ObjectBusinessLayer) {
			$this->obl = $objectBusinessLayer;		
		}
	}


	/**
	 * get a param
	 * @param string $key
	 * @param mixed $default
	 * @return mixed $value
	 */
	public function params($key, $default=null){
		$value = parent::params($key, $default);
		if ($default !== null) {
			if ($default instanceof DateTime) {
				$value = DateTime::createFromFormat(DateTime::ISO8601, $value);
			} else {
				settype($value, gettype($default));
			}
		}
		return $value;
	}


	/*
	 * Lets you access http request header
	 * @param string $key the key which you want to access in the http request header
	 * @param mixed $default If the key is not found, this value will be returned
	 * @return mixed content of header field
	 */
	protected function header($key, $type='string', $default=null){
		$key = 'HTTP_' . strtoupper($key);

		$key = str_replace('-', '_', $key);

		if (isset($this->request->server[$key]) === false) {
			return $default;
		} else {
			$value = $this->request->server[$key];
			if (strtolower($type) === 'datetime') {
				$value = \DateTime::createFromFormat(\DateTime::ISO8601);
			} else {
				settype($value, $type);
			}
			return $value;
		}
	}


	/*
	 * get accept header
	 * @return string
	 */
	protected function accept() {
		$accept = $this->header('accept');

		if (substr_count($accept, ',')) {
			list($accept) = explode(',', $accept);
		}
		if (substr_count($accept, ';')) {
			list($accept) = explode(';', $accept);
		}

		return $accept;
	}


	/*
	 * get content type header
	 * @return string
	 */
	protected function contentType() {
		$contentType = $this->header('content-type');

		if (substr_count($contentType, ';')) {
			list($contentType) = explode(';', $contentType);
		}

		return $contentType;
	}
}