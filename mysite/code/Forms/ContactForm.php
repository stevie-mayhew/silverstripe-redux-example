<?php

/**
 * Class ContactForm
 */
class ContactForm extends Form
{
    /**
     * @param Controller $controller
     * @param String $name
     */
    public function __construct($controller, $name)
    {
        // if you add fields here, remember to add them to 'ContactRequest.php' and 'ContactForm.ss'

        $fields = FieldList::create(
            TextField::create("Name", "Name")
                ->addExtraClass("form-control")
                ->setAttribute('placeholder', 'Full Name')
                ->setAttribute('required', 'true')
                ->setAttribute('id', 'Name'),

            TextField::create("Phone", "Phone")
                ->addExtraClass("form-control")
                ->setAttribute('placeholder', 'Phone or mobile')
                ->setAttribute('id', 'Phone'),

            EmailField::create("Email", "Email")
                ->addExtraClass("form-control")
                ->setAttribute('placeholder', 'Email')
                ->setAttribute('required', 'true')
                ->setAttribute('id', 'Email'),

            // please alter maxlength as per client requirements
            TextareaField::create("Message", "Message")
                ->addExtraClass("form-control")
                ->setAttribute('placeholder', 'How can we help?')
                ->setAttribute('required', 'true')
                ->setAttribute('id', 'Message'),

            HoneyPotField::create('Information', 'Information')
        );


        $actions = FieldList::create(
            FormAction::create("doSubmit", "Submit")
        );

        parent::__construct($controller, $name, $fields, $actions);

        // enable Akismet spam protection
        if (class_exists('AkismetSpamProtector')) {
            $this->enableSpamProtection(
                array(
                    'mapping' => array(
                        'Name' => 'authorName',
                        'Email' => 'authorMail',
                        'Message' => 'body'
                    )
                )
            );
        }

        if (Session::get("FormInfo.{$this->FormName()}.data")) {
            $this->loadDataFrom(Session::get("FormInfo.{$this->FormName()}.data"));
        } else {
            $this->loadDataFrom($this->getRequest()->postVars());
        }

    }

    /**
     * @return bool
     */
    public function validate()
    {
        // clear out any previous messaging we have sent to the user
        Session::clear("FormInfo.{$this->FormName()}.formError.message");
        Session::clear("FormInfo.{$this->FormName()}.formError.type");

        $valid = parent::validate();
        if (!$valid) {
            // if the form isn't valid at this stage then return the errors which are coming first
            return $valid;
        }

        $data = $this->getData();
        Session::set("FormInfo.{$this->FormName()}.data", $data);

        $isSpam = false;
        $spamMessage = "Your contact form contains content that is deemed not suitable to send."
            . "Please review or contact us via email.";

        /**
         * Test 1:
         *
         * The following PHP code, when placed on your form processing page (the place where the form is submitted to),
         * will search all of the form elements for the most common header injections and other code that may trick your
         * mail processor into sending carbon copy or blind carbon copy messages to others. It also detects any content
         * that includes the string "[url" which is used by most forum software to specify links.
         * If any are found, it sets the $spam variable to true.
         */
        if (preg_match("/bcc:|cc:|multipart|\[url|Content-Type:/i", implode($data, ' '))) {
            $isSpam = true;
        }

        /**
         * Test 2:
         *
         * The following will set the $spam variable if more than $allowed instance(s) of "<a" or "http:" appear
         * anywhere within the form. You can change the $allowedURLs variable to zero or more if needed for any client
         * circumstance.
         */
        $patternAnchor = '/(<a)+/i';
        $patternHttp = '/(http:)/i';
        $patternHttps = '/(https:)/i';
        $patternWww = '/(www\.)/i';
        $allowedURLs = 1;
        preg_match_all($patternAnchor, $data['Message'], $anchorMatches);
        preg_match_all($patternHttp, $data['Message'], $httpMatches);
        preg_match_all($patternHttps, $data['Message'], $httpsMatches);
        preg_match_all($patternWww, $data['Message'], $wwwMatches);


        if (
            (
                !empty($anchorMatches[0]) ||
                !empty($httpMatches[0]) ||
                !empty($httpsMatches[0]) ||
                !empty($wwwMatches[0])
            )
            &&
            (
                count($anchorMatches[0]) > $allowedURLs ||
                count($httpMatches[0]) > $allowedURLs ||
                count($httpsMatches[0]) > $allowedURLs ||
                count($wwwMatches[0]) > $allowedURLs
            )
        ) {
            $spamMessage .= "\nPlease include no more than {$allowedURLs} url(s) in your message.";
            $isSpam = true;
        }

        /**
         * Test 3:
         *
         * Basic word filtering. Add to this as necessary - have fun!
         */
        $pattern = '/\b(?:anal|anus|arse|ass|ballsack|balls|bastard|bitch|biatch|bloody|blowjob|blow job|bollock|bollok|boner|boob|bugger|bum|butt|buttplug|clitoris|cock|coon|crap|cunt|damn|dick|dildo|dyke|fag|feck|fellate|fellatio|felching|fuck|f u c k|fudgepacker|fudge packer|flange|Goddamn|God damn|hell|homo|jerk|jizz|knobend|knob end|labia|lmao|lmfao|muff|nigger|nigga|omg|penis|piss|poop|prick|pube|pussy|scrotum|sex|shit|s hit|sh1t|slut|smegma|spunk|tit|tosser|turd|twat|vagina|wank|whore|wtf|ambien|anal|bulgary|cheapest pills|cialis|credit|discount|ejaculation|enlargement|levitra|money|mortgage|natural weight loss|omega|online pharmacy|penis|pharmaceuticals|porno|premature|prescription|refinance|rolex|sex|soma|valium|xanax)\b/i';
        preg_match_all($pattern, implode($data, ' '), $matches);

        if (!empty($matches[0])) {
            $isSpam = true;
        }

        // if content deemed to be unsuitable we let user know
        if ($isSpam) {
            $this->sessionMessage($spamMessage, 'bad');
            $this->validator->validationError('Spam', $spamMessage, 'bad');
            return false;
        }

        return true;
    }

    /**
     * @param $data
     * @param Form $form
     * @return SS_HTTPResponse
     */
    public function doSubmit($data, Form $form)
    {
        $siteConfig = SiteConfig::current_site_config();

        $contactRequest = new ContactRequest();
        $form->saveInto($contactRequest);
        $contactRequest->write();

        $to = $siteConfig->ContactEmail ? $siteConfig->ContactEmail : Config::inst()->get('Email', 'admin_email');
        $from = $data['Email'];
        $name = $data['Name'];
        $phone = $data['Phone'];
        $subject = isset($data['Subject']) && $data['Subject'] ? $data['Subject'] : 'Website Enquiry from ' . $data['Name'];

        $message = 'Name: ' . $name . '<br />';
        $message .= 'Phone: ' . $phone . '<br />';
        $message .= 'Email: ' . $from . '<br /><br />';
        $message .= '----------------------------------' . '<br /><br />';
        $message .= Convert::html2raw($data['Message']) . '<br /><br />';
        $message .= '----------------------------------' . '<br /><br />';
        $message .= '<small>Sent from IP: ' . $_SERVER['REMOTE_ADDR'] . '</small>';

        $email = new Email();
        $email->setTo($to);
        $email->setFrom($to);
        $email->replyTo($from);
        $email->setSubject($subject);
        $email->setBody($message);
        $email->send();

        // clear the data for the form after submission
        Session::clear("FormInfo.{$this->FormName()}.data");
        Session::clear("FormInfo.{$this->FormName()}.message");
        Session::clear("FormInfo.{$this->FormName()}.formError.message");
        Session::clear("FormInfo.{$this->FormName()}.formError.type");
        Session::clear("FormInfo.{$this->FormName()}.errors");

        return $form->getController()->redirect($form->getController()->Link() . "?cs=1");
    }

}
