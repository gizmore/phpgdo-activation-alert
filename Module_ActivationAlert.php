<?php
namespace GDO\ActivationAlert;

use GDO\Core\GDO_Module;
use GDO\User\GDO_User;
use GDO\Mail\Mail;
use GDO\Net\GDT_IP;
use GDO\Mail\GDT_Email;
use GDO\Register\GDO_UserActivation;

/**
 * Sends a mail when a new user is activated.
 * 
 * @author gizmore
 * @version 7.0.1
 */
final class Module_ActivationAlert extends GDO_Module
{
	public function onLoadLanguage() : void
	{
		$this->loadLanguage('lang/activation_alert');
	}
	
	public function getConfig() : array
	{
		return [
			GDT_Email::make('activation_alert_mail_receiver')->initial(GDO_ADMIN_EMAIL),
		];
	}
	
	public function cfgMailReceiver() { return $this->getConfigVar('activation_alert_mail_receiver'); }
	
	############
	### Hook ###
	############
	public function hookUserActivated(GDO_User $user, GDO_UserActivation $activation=null)
	{
		$this->sendMails($user);
	}
	
	private function sendMails(GDO_User $user)
	{
		if ($to = $this->cfgMailReceiver())
		{
			$this->sendSingleMail($to, $user);
		}
		else
		{
			foreach (GDO_User::admins() as $admin)
			{
				$this->sendMail($admin, $user, false);
			}
		}
	}
	
	private function sendSingleMail($to, GDO_User $user)
	{
		$mail = Mail::botMail();
		$mail->setSubject(tiso(GDO_LANGUAGE, 'mail_subj_user_activated_staff', [sitename()]));
		$tVars = array(
			'Admin',
			sitename(),
			$user->renderUserName(),
			GDT_IP::current(),
		);
		$mail->setBody(tiso(GDO_LANGUAGE, 'mail_body_user_activated_staff', $tVars));
		$mail->setReceiver($to);
		$mail->sendAsHTML();
	}
	
	private function sendMail(GDO_User $admin, GDO_User $user, bool $faked)
	{
		$mail = Mail::botMail();
		$mail->setSubject(tusr($admin, 'mail_subj_user_activated_staff', [sitename()]));
		$tVars = array(
			$admin->renderUserName(),
			sitename(),
			$user->renderUserName(),
			GDT_IP::current(),
		);
		$mail->setBody(tusr($admin, 'mail_body_user_activated_staff', $tVars));
		$mail->sendToUser($admin);
	}
	
}

