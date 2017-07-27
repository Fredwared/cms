<?php
namespace App\Models\Entity\Business\Traits\Attribute;

trait UserAttribute
{
    public function getAccountId()
    {
        return $this->id;
    }

    public function getLandingPage()
    {
        return $this->landing_page;
    }

    public function getDefaultLanguage()
    {
        return $this->default_language;
    }

    public function hasChangePassLogin()
    {
        return $this->change_pass_first_login;
    }

    public function stayDetailPage()
    {
        return $this->action_after_save == 'detail' ? true : false;
    }
}
