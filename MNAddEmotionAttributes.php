<?php
namespace MNAddEmotionAttributes;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;




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
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Listing' => 'onFrontendListing',
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onFrontend'
        ];
    }

    /**
     * @param InstallContext $context
     * @throws \Exception
     */
    public function install(InstallContext $context)
    {
        $service = $this->container->get('shopware_attribute.crud_service');
        $service->update('s_emotion_attributes', 'mnposition', 'combobox', [
            'label' => 'Position der Einkaufswelt',
            'displayInBackend' => true,
            'arrayStore' => [
                ['key' => '1', 'value' => 'Vor Listing'],
                ['key' => '2', 'value' => 'Nach Listing']
            ],
        ]);
    }

    /**
     * @param UninstallContext $context
     * @throws \Exception
     */
    public function uninstall(UninstallContext $context)
    {
        $service = $this->container->get('shopware_attribute.crud_service');
        $service->delete('s_emotion_attributes', 'mnposition');
    }


    /**
     * @param \Enlight_Event_EventArgs $args
     * @throws \Exception
     */

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

    public function onFrontend(\Enlight_Event_EventArgs $args)
    {
        if ( Shopware()->Config()->getByNamespace('MNAddEmotionAttributes', 'useEmotionAttribute') == 'Yes')
        {
            /** @var \Enlight_Controller_Action $controller */
            $controller = $args->get('subject');
            $view = $controller->View();
            $this->container->get('Template')->addTemplateDir(
                $this->getPath() . '/Resources/views/'
            );
        }
    }
}
