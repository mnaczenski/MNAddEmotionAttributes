<?php
namespace MNAddEmotionAttributes;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
class MNAddEmotionAttributes extends \Shopware\Components\Plugin
{
    public function activate(ActivateContext $context)
    {
        $context->scheduleClearCache(ActivateContext::CACHE_LIST_DEFAULT);
    }
    public function deactivate(DeactivateContext $context)
    {
        $context->scheduleClearCache(DeactivateContext::CACHE_LIST_DEFAULT);
    }
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Listing' => 'onFrontendListing'
        ];
    }
    public function onFrontendListing(\Enlight_Event_EventArgs $args)
    {
        /** @var \Enlight_Controller_Action $controller */
        $controller = $args->get('subject');
        $view = $controller->View();

        $emotions = $view->getAssign('emotions');


        $service = $this->container->get('shopware_attribute.data_loader');

        $i = 0;
        foreach ($emotions as $emotion)
        {
            $attributes['attributes'] = $service->load('s_emotion_attributes', $emotion['id']);
            $emotions[$i] = $emotions[$i] + $attributes;
            $i++;
        }

        $view->assign('emotions', $emotions);
    }
}
