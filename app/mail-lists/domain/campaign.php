<?php

namespace Maven\MailLists\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class Compaign extends \Maven\Core\DomainObject{
	
	private $webId;
	private $listId;
	private $folderId;
	private $templateId;
	private $contentType;
	private $title;
	private $type;
	private $createTime;
	private $sendTime;
	private $emailsSent;
	private $status;
	private $fromName;
	private $fromEmail;
	private $subject;
	private $toName;
	private $archiveUrl;
	private $inlineCss;
	private $analytics;
	private $analyticsTag;
	private $authenticate;
	private $ecomm360;
	private $autoTweet;
	private $autoFbPost;
	private $autoFooter;

	public function getWebId() {
		return $this->webId;
	}

	public function setWebId( $webId ) {
		$this->webId = $webId;
	}

	public function getListId() {
		return $this->listId;
	}

	public function setListId( $listId ) {
		$this->listId = $listId;
	}

	public function getFolderId() {
		return $this->folderId;
	}

	public function setFolderId( $folderId ) {
		$this->folderId = $folderId;
	}

	public function getTemplateId() {
		return $this->templateId;
	}

	public function setTemplateId( $templateId ) {
		$this->templateId = $templateId;
	}

	public function getContentType() {
		return $this->contentType;
	}

	public function setContentType( $contentType ) {
		$this->contentType = $contentType;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setTitle( $title ) {
		$this->title = $title;
	}

	public function getType() {
		return $this->type;
	}

	public function setType( $type ) {
		$this->type = $type;
	}

	public function getCreateTime() {
		return $this->createTime;
	}

	public function setCreateTime( $createTime ) {
		$this->createTime = $createTime;
	}

	public function getSendTime() {
		return $this->sendTime;
	}

	public function setSendTime( $sendTime ) {
		$this->sendTime = $sendTime;
	}

	public function getEmailsSent() {
		return $this->emailsSent;
	}

	public function setEmailsSent( $emailsSent ) {
		$this->emailsSent = $emailsSent;
	}

	public function getStatus() {
		return $this->status;
	}

	public function setStatus( $status ) {
		$this->status = $status;
	}

	public function getFromName() {
		return $this->fromName;
	}

	public function setFromName( $fromName ) {
		$this->fromName = $fromName;
	}

	public function getFromEmail() {
		return $this->fromEmail;
	}

	public function setFromEmail( $fromEmail ) {
		$this->fromEmail = $fromEmail;
	}

	public function getSubject() {
		return $this->subject;
	}

	public function setSubject( $subject ) {
		$this->subject = $subject;
	}

	public function getToName() {
		return $this->toName;
	}

	public function setToName( $toName ) {
		$this->toName = $toName;
	}

	public function getArchiveUrl() {
		return $this->archiveUrl;
	}

	public function setArchiveUrl( $archiveUrl ) {
		$this->archiveUrl = $archiveUrl;
	}

	public function getInlineCss() {
		return $this->inlineCss;
	}

	public function setInlineCss( $inlineCss ) {
		$this->inlineCss = $inlineCss;
	}

	public function getAnalytics() {
		return $this->analytics;
	}

	public function setAnalytics( $analytics ) {
		$this->analytics = $analytics;
	}

	public function getAnalyticsTag() {
		return $this->analyticsTag;
	}

	public function setAnalyticsTag( $analyticsTag ) {
		$this->analyticsTag = $analyticsTag;
	}

	public function getAuthenticate() {
		return $this->authenticate;
	}

	public function setAuthenticate( $authenticate ) {
		$this->authenticate = $authenticate;
	}

	public function getEcomm360() {
		return $this->ecomm360;
	}

	public function setEcomm360( $ecomm360 ) {
		$this->ecomm360 = $ecomm360;
	}

	public function getAutoTweet() {
		return $this->autoTweet;
	}

	public function setAutoTweet( $autoTweet ) {
		$this->autoTweet = $autoTweet;
	}

	public function getAutoFbPost() {
		return $this->autoFbPost;
	}

	public function setAutoFbPost( $autoFbPost ) {
		$this->autoFbPost = $autoFbPost;
	}

	public function getAutoFooter() {
		return $this->autoFooter;
	}

	public function setAutoFooter( $autoFooter ) {
		$this->autoFooter = $autoFooter;
	}


	
}