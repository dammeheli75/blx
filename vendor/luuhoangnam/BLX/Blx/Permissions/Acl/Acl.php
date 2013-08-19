<?php
namespace Blx\Permissions\Acl;

use Zend\I18n\Translator\Translator;

class Acl extends \Zend\Permissions\Acl\Acl
{

    protected $translator;

    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function getTranslator()
    {
        return $this->translator;
    }

    public function getPrivilegeList()
    {
        return array(
            'control_panel' => array(
                'access' => $this->translator->translate('Truy cap trang quan tri')
            ),
            'profile' => array(
                'managemnet' => $this->translator->translate('Quan ly ho so'),
                'read' => $this->translator->translate('Xem danh sach ho so'),
                'insert' => $this->translator->translate('Them ho so'),
                'update' => $this->translator->translate('Sua ho so'),
                'delete' => $this->translator->translate('Xoa ho so')
            ),
            'venue' => array(
                'managemnet' => $this->translator->translate('Quan ly dia diem thi'),
                'read' => $this->translator->translate('Xem danh sach dia diem thi'),
                'insert' => $this->translator->translate('Them dia diem thi'),
                'update' => $this->translator->translate('Sua dia diem thi'),
                'delete' => $this->translator->translate('Xoa dia diem thi')
            ),
            'post_category' => array(
                'managemnet' => $this->translator->translate('Quan ly chuyen muc tin tuc'),
                'read' => $this->translator->translate('Xem danh sach chuyen muc'),
                'insert' => $this->translator->translate('Them chuyen muc'),
                'update' => $this->translator->translate('Sua chuyen muc'),
                'delete' => $this->translator->translate('Xoa chuyen muc')
            ),
            'post' => array(
                'managemnet' => $this->translator->translate('Quan ly bai viet'),
                'read' => $this->translator->translate('Xem danh sach bai viet'),
                'insert' => $this->translator->translate('Them bai viet'),
                'update' => $this->translator->translate('Sua bai viet'),
                'delete' => $this->translator->translate('Xoa bai viet')
            ),
            'user_group' => array(
                'managemnet' => $this->translator->translate('Quan ly nhom thanh vien'),
                'read' => $this->translator->translate('Xem danh sach nhom thanh vien'),
                'insert' => $this->translator->translate('Them thanh vien'),
                'update' => $this->translator->translate('Sua thanh vien'),
                'delete' => $this->translator->translate('Xoa thanh vien')
            ),
            'user' => array(
                'managemnet' => $this->translator->translate('Quan ly thanh vien'),
                'read' => $this->translator->translate('Xem danh sach thanh vien'),
                'insert' => $this->translator->translate('Them thanh vien'),
                'update' => $this->translator->translate('Sua thanh vien'),
                'delete' => $this->translator->translate('Xoa thanh vien')
            ),
            'permission' => array(
                'read' => $this->translator->translate('Xem quyen truy cap'),
                'update' => $this->translator->translate('Sua phan quyen')
            ),
            'setting' => array(
                'read' => $this->translator->translate('Xem cac thiet lap'),
                'update' => $this->translator->translate('Sua thiet lap')
            )
        );
    }
}