<?php
/**
 * @link http://buildwithcraft.com/
 * @copyright Copyright (c) 2013 Pixel & Tonic, Inc.
 * @license http://buildwithcraft.com/license
 */

namespace craft\app\controllers;

use Craft;
use craft\app\errors\HttpException;
use craft\app\models\AccountSettings as AccountSettingsModel;
use craft\app\models\SiteSettings as SiteSettingsModel;
use craft\app\web\Controller;

/**
 * The InstallController class is a controller that directs all installation related tasks such as creating the database
 * schema and default content for a Craft installation.
 *
 * Note that all actions in the controller are open to do not require an authenticated Craft session in order to execute.
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 3.0
 */
class InstallController extends Controller
{
	// Properties
	// =========================================================================

	/**
	 * @inheritdoc
	 */
	protected $allowAnonymous = true;

	// Public Methods
	// =========================================================================

	/**
	 * @inheritDoc Controller::init()
	 *
	 * @throws HttpException
	 * @return null
	 */
	public function init()
	{
		// Return a 404 if Craft is already installed
		if (!Craft::$app->config->get('devMode') && Craft::$app->isInstalled())
		{
			throw new HttpException(404);
		}
	}

	/**
	 * Index action.
	 *
	 * @return null
	 */
	public function actionIndex()
	{
		Craft::$app->runController('templates/requirementscheck');

		// Guess the site name based on the server name
		$server = Craft::$app->getRequest()->getServerName();
		$words = preg_split('/[\-_\.]+/', $server);
		array_pop($words);
		$vars['defaultSiteName'] = implode(' ', array_map('ucfirst', $words));
		$vars['defaultSiteUrl'] = 'http://'.$server;

		$this->renderTemplate('_special/install', $vars);
	}

	/**
	 * Validates the user account credentials.
	 *
	 * @return null
	 */
	public function actionValidateAccount()
	{
		$this->requirePostRequest();
		$this->requireAjaxRequest();

		$accountSettings = new AccountSettingsModel();
		$username = Craft::$app->getRequest()->getBodyParam('username');
		if (!$username)
		{
			$username = Craft::$app->getRequest()->getBodyParam('email');
		}

		$accountSettings->username = $username;
		$accountSettings->email = Craft::$app->getRequest()->getBodyParam('email');
		$accountSettings->password = Craft::$app->getRequest()->getBodyParam('password');

		if ($accountSettings->validate())
		{
			$return['validates'] = true;
		}
		else
		{
			$return['errors'] = $accountSettings->getErrors();
		}

		$this->returnJson($return);
	}

	/**
	 * Validates the site settings.
	 *
	 * @return null
	 */
	public function actionValidateSite()
	{
		$this->requirePostRequest();
		$this->requireAjaxRequest();

		$siteSettings = new SiteSettingsModel();
		$siteSettings->siteName = Craft::$app->getRequest()->getBodyParam('siteName');
		$siteSettings->siteUrl = Craft::$app->getRequest()->getBodyParam('siteUrl');

		if ($siteSettings->validate())
		{
			$return['validates'] = true;
		}
		else
		{
			$return['errors'] = $siteSettings->getErrors();
		}

		$this->returnJson($return);
	}

	/**
	 * Install action.
	 *
	 * @return null
	 */
	public function actionInstall()
	{
		$this->requirePostRequest();
		$this->requireAjaxRequest();

		// Run the installer
		$username = Craft::$app->getRequest()->getBodyParam('username');

		if (!$username)
		{
			$username = Craft::$app->getRequest()->getBodyParam('email');
		}

		$inputs['username']   = $username;
		$inputs['email']      = Craft::$app->getRequest()->getBodyParam('email');
		$inputs['password']   = Craft::$app->getRequest()->getBodyParam('password');
		$inputs['siteName']   = Craft::$app->getRequest()->getBodyParam('siteName');
		$inputs['siteUrl']    = Craft::$app->getRequest()->getBodyParam('siteUrl');
		$inputs['locale'  ]   = Craft::$app->getRequest()->getBodyParam('locale');

		Craft::$app->install->run($inputs);

		$return = ['success' => true];
		$this->returnJson($return);
	}
}
