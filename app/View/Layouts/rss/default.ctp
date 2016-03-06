<?php
if (!isset($documentData)) {
    $documentData = array();
}
if (!isset($channelData)) {
    $channelData = array();
}
if (!isset($channelData['title'])) {
    $channelData['title'] = $this->fetch('title');
}
$channel = $this->Rss->channel(array(), $channelData, $this->fetch('content'));

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo $this->Rss->document($documentData, $channel);