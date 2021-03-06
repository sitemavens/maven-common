<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
    exit;

class MailFormatter {

    public function __construct () {
        ;
    }

    public static function init () {

        HookManager::instance()->addFilter( 'wp_mail', array( __CLASS__, 'processEmail' ) );
        HookManager::instance()->addFilter( 'mandrill_payload', array( __CLASS__, 'processWpMandrillEmail' ) );
    }

    public static function processEmail ( $args ) {
        if ( \Maven\Settings\MavenRegistry::instance()->isDisabledTemplate() && \Maven\Core\Request::current()->isPost() && \Maven\Core\Request::current()->exists( 'gform_submit' ) ) {
            $useTemplate = false;
        } else {
            $useTemplate = true;
        }
        $messageContent = "";
        if ( isset( $args['message'] ) ) {
            $messageContent = $args['message'];
        } else if ( isset( $args['html'] ) ) {
            $messageContent = $args['html'];
        }

        $message = self::prepareContentEmail( $messageContent, $useTemplate );
//		
        //Change content to html
        $newHeaders = $args['headers'];
        if ( !is_array( $newHeaders ) ) {
            $newHeaders = explode( "\n", str_replace( "\r\n", "\n", $args['headers'] ) );
        }
        $newHeaders[] = "Content-type:text/html;";

        $newMailArgs = array(
            'to' => $args['to'],
            'subject' => $args['subject'],
            'message' => $message,
            'headers' => $newHeaders,
            'attachments' => $args['attachments']
        );

        return $newMailArgs;
    }

    public static function processWpMandrillEmail ( $args ) {
        if ( \Maven\Settings\MavenRegistry::instance()->isDisabledTemplate() && \Maven\Core\Request::current()->isPost() && \Maven\Core\Request::current()->exists( 'gform_submit' ) ) {
            $useTemplate = false;
        } else {
            $useTemplate = true;
        }
//        $useTemplate = \Maven\Settings\MavenRegistry::instance()->isDisabledTemplate()
        if ( isset( $args['message'] ) ) {
            $args['message'] = self::prepareContentEmail( $args['message'], $useTemplate );
        } else if ( isset( $args['html'] ) ) {
            $args['html'] = self::prepareContentEmail( $args['html'], $useTemplate );
        }

        return $args;
    }

    public static function prepareContentEmail ( $message, $useTemplate = true ) {

        $registry = \Maven\Settings\MavenRegistry::instance();

        $templateContent = "";

        if ( $useTemplate ) {

            // We need to get the template file  
            $templateFile = $registry->getCurrentEmailThemePath();
            $templateFileFullPath = HookManager::instance()->applyFilters( 'maven/cart/emailHeaderFullPath', '' );
            $templateContent = $templateFileFullPath ? \Maven\Core\Loader::getFileContent( $templateFileFullPath ) : \Maven\Core\Loader::getFileContent( $templateFile );
        }

        $date = new \Maven\Core\MavenDateTime();
        $date = $date->getDateFormated();

        $mailVariables = array(
            'year' => date( "Y" ),
            'month' => date( 'M' ),
            'date' => $date,
            'message' => $message,
            'title' => "",
            'organization_name' => $registry->getOrganizationName(),
            'organization_logo' => $registry->getOrganizationLogoFullUrl(),
            'website_url' => $registry->getWebSiteUrl(),
            'signature' => $registry->getSignature(),
            'background_color' => $registry->getEmailBackgroundColor()
        );


        $templateProcessor = new \Maven\Core\TemplateProcessor( $message, $mailVariables );
        if ( $useTemplate ) {
            // Process the whole template
            $filledTemplate = $templateProcessor->getProcessedTemplate( $templateContent );
            \Maven\Loggers\Logger::log()->message( "\Maven\Core\MailFormatter\PrepareContentEmail: With template" );
//			die( print_r( $filledTemplate, true ) );
            return $filledTemplate;
        } else {
            \Maven\Loggers\Logger::log()->message( "\Maven\Core\MailFormatter\PrepareContentEmail: Without template" );
            $message = $templateProcessor->getProcessedTemplate();
        }
        return $message;
    }

}
