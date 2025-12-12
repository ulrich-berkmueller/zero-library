<?php

namespace Gwa\Wordpress\Zero\Traits;

/**
 * Add trait to a module to add control boxes and controls.
 */
trait AddThemeCustomization
{
    private $wpcustomize;

    private $isCustomizationInited = false;

    public function customizeRegisterAction($wpcustomize)
    {
        $this->wpcustomize = $wpcustomize;
        $this->customize();
    }

    protected function initCustomization()
    {
        if (!$this->isCustomizationInited) {
            $this->isCustomizationInited = true;
            $this->getWpBridge()->addAction('customize_register', [$this, 'customizeRegisterAction']);
        }
    }

    protected function addSection($name, array $options)
    {
        $this->wpcustomize->add_section($name, $options);

        return $this;
    }

    protected function addSetting($name, $options)
    {
        $this->wpcustomize->add_setting($name, $options);

        return $this;
    }

    protected function addControl($name, $options)
    {
        $this->wpcustomize->add_control(
            new \WP_Customize_Control(
                $this->wpcustomize,
                $name,
                $options
            )
        );

        return $this;
    }

    abstract protected function customize();

    abstract protected function getWpBridge();
}
