<?php

require_once(realpath((dirname(__FILE__)) . "/../AbstractFixtures.php"));

class TopicFixtures extends AbstractFixtures
{

    public static function getOrder()
    {
        return 2;
    }

    public function load()
    {
        $oUserGolfer = $this->getReference('user-golfer');
        $oBlogGadgets = $this->getReference('blog-gadgets');

        $oTopicToshiba = $this->_createTopic($oBlogGadgets->getBlogId(), $oUserGolfer->getId(),
            'Toshiba unveils 13.3-inch AT330 Android ICS 4.0 tablet',
            'Toshiba is to add a new Android 4.0 ICS to the mass which is known as Toshiba AT330. The device is equipped with a multi-touch capacitive touch display that packs a resolution of 1920 x 1200 pixels. The Toshiba AT330 tablet is currently at its prototype stage. We have very little details about the tablet, knowing that it’ll come equipped with HDMI port, on-board 32GB storage that’s expandable via an full-sized SD card slot. It’ll also have a built-in TV tuner and a collapsible antenna.It’ll also run an NVIDIA Tegra 3 quad-core processor. Other goodies will be a 1.3MP front-facing camera and a 5MP rear-facing camera. Currently, there is no information about its price and availability. A clip is included below showing it in action.',
            'gadget');
        $this->addReference('topic-toshiba', $oTopicToshiba);

        $oTopicIpad = $this->_createTopic($oBlogGadgets->getBlogId(), $oUserGolfer->getId(),
            'iPad 3 rumored to come this March with quad-core chip and 4G LTE',
            'Another rumor for the iPad 3 has surfaced with some details given by Bloomberg, claiming that the iPad 3 production is already underway and will be ready for a launch as early as March.',
            'apple, ipad');
        $this->addReference('topic-ipad', $oTopicIpad);

        $oPersonalBlogGolfer = $this->oEngine->Blog_GetPersonalBlogByUserId($oUserGolfer->getId());
        $oTopicSony = $this->_createTopic($oPersonalBlogGolfer->getBlogId(), $oUserGolfer->getId(),
            'Sony MicroVault Mach USB 3.0 flash drive',
            'Want more speeds and better protection for your data? The Sony MicroVault Mach flash USB 3.0 drive is what you need. It offers the USB 3.0 interface that delivers data at super high speeds of up to 5Gbps. It’s also backward compatible with USB 2.0.',
            'sony, flash, gadget');
        $this->addReference('topic-sony', $oTopicSony);
    }

    /**
     * Create topic with default values
     *
     * @param int $iBlogId
     * @param int $iUserId
     * @param string $sTitle
     * @param string $sText
     * @param string $sTags
     *
     * @return ModuleTopic_EntityTopic
     */
    private function _createTopic($iBlogId, $iUserId, $sTitle, $sText, $sTags)
    {
        $oTopic = Engine::GetEntity('Topic');
        /* @var $oTopic ModuleTopic_EntityTopic */
        $oTopic->setBlogId($iBlogId);
        $oTopic->setUserId($iUserId);
        $oTopic->setUserIp('127.0.0.1');
        $oTopic->setForbidComment(false);
        $oTopic->setType('topic');
        $oTopic->setTitle($sTitle);
        $oTopic->setPublish(true);
        $oTopic->setPublishIndex(true);
        $oTopic->setPublishDraft(true);
        $oTopic->setDateAdd(date("Y-m-d H:i:s")); // @todo freeze
        $oTopic->setTextSource($sText);
        list($sTextShort, $sTextNew, $sTextCut) = $this->oEngine->Text_Cut($oTopic->getTextSource());

        $oTopic->setCutText($sTextCut);
        $oTopic->setText($this->oEngine->Text_Parser($sTextNew));
        $oTopic->setTextShort($this->oEngine->Text_Parser($sTextShort));

        $oTopic->setTextHash(md5($oTopic->getType() . $oTopic->getTextSource() . $oTopic->getTitle()));
        $oTopic->setTags($sTags);

        $this->oEngine->Topic_AddTopic($oTopic);

        return $oTopic;
    }
}

