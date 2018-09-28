<?php

namespace PagarMe\Magento\Test\Helper;

trait AdminDataProvider
{
    public function getAdminPassword()
    {
        return 'admin123';
    }

    public function createAdminUser()
    {
        $adminUser = \Mage::getModel('admin/user')
            ->setData(
                array(
                    'username'  => mktime() . '_admin',
                    'firstname' => 'Admin',
                    'lastname'  => 'Admin',
                    'email'     => mktime() . '@admin.com',
                    'password'  => $this->getAdminPassword(),
                    'is_active' => 1
                )
            )->save();

        $adminUser->setRoleIds(
            array(1)
        )
        ->setRoleUserId($adminUser->getUserId())
        ->saveRelations();

        return $adminUser;
    }
}
