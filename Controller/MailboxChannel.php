<?php

namespace Webkul\UVDesk\MailboxBundle\Controller;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MailboxChannel extends Controller
{
    public function loadSettings(Request $request)
    {
        $mailboxCollection = $this->getRegisteredMailboxes();

        return $this->render('@UVDeskMailbox//settings.html.twig', [
            'swiftmailers' => $this->container->get('swiftmailer.service')->getSwiftmailerIds(),
            'mailboxes' => json_encode($mailboxCollection)
        ]);
    }

    private function getRegisteredMailboxes()
    {
        // Fetch existing content in file
        $filePath = dirname(__FILE__, 5) . '/config/packages/uvdesk_mailbox.yaml';
        $file_content = file_get_contents($filePath);

        // Convert yaml file content into array and merge existing mailbox and new mailbox
        $file_content_array = Yaml::parse($file_content, 6);

        if ($file_content_array['uvdesk_mailbox']['mailboxes']) {
            foreach ($file_content_array['uvdesk_mailbox']['mailboxes'] as $key => $value) {
                $value['mailbox_id'] = $key;
                $mailboxCollection[] = $value;
            }
        }

        return $mailboxCollection ?? [];
    }
}