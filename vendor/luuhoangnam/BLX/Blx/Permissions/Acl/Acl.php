<?php
namespace Blx\Permissions\Acl;

class Acl extends \Zend\Permissions\Acl\Acl
{
    public function getPrivilegeList()
    {
        return array(
            'control_panel' => array(
                'access' => 'Truy cap trang quan tri'
            ),
            'profile' => array(
                'managemnet' => 'Quan ly ho so',
                'quick_search' => 'Tim kiem nhanh',
                'read' => 'Xem danh sach ho so',
                'create' => 'Them ho so',
                'update' => 'Sua ho so',
                'delete' => 'Xoa ho so'
            ),
            'venue' => array(
                'managemnet' => 'Quan ly dia diem thi',
                'read' => 'Xem danh sach dia diem thi',
                'create' => 'Them dia diem thi',
                'update' => 'Sua dia diem thi',
                'delete' => 'Xoa dia diem thi'
            ),
            'post_category' => array(
                'managemnet' => 'Quan ly chuyen muc tin tuc',
                'read' => 'Xem danh sach chuyen muc',
                'create' => 'Them chuyen muc',
                'update' => 'Sua chuyen muc',
                'delete' => 'Xoa chuyen muc'
            ),
            'post' => array(
                'managemnet' => 'Quan ly bai viet',
                'read' => 'Xem danh sach bai viet',
                'create' => 'Them bai viet',
                'update' => 'Sua bai viet',
                'delete' => 'Xoa bai viet'
            ),
            'user_group' => array(
                'managemnet' => 'Quan ly nhom thanh vien',
                'read' => 'Xem danh sach nhom thanh vien',
                'create' => 'Them thanh vien',
                'update' => 'Sua thanh vien',
                'delete' => 'Xoa thanh vien'
            ),
            'user' => array(
                'managemnet' => 'Quan ly thanh vien',
                'read' => 'Xem danh sach thanh vien',
                'create' => 'Them thanh vien',
                'update' => 'Sua thanh vien',
                'delete' => 'Xoa thanh vien'
            ),
            'permission' => array(
                'read' => 'Xem quyen truy cap',
                'update' => 'Sua quyen truy cap'
            ),
            'setting' => array(
                'read' => 'Xem cac thiet lap',
                'update' => 'Sua thiet lap'
            )
        );
    }
}