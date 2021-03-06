$(document).ready(function () {

    var grid = $("#grid").kendoGrid({
        columns: [
            {
                field: "ID",
                title: "ID",
                width: 60,
                attributes: {
                    style: "text-align: center;"
                }
            },
            {
                field: "title",
                title: "Tên nhóm",
                width: 300
            },
            {
                field: "memberCount",
                title: "Số thành viên",
                width: 140,
                attributes: {
                    style: "text-align: center;"
                }
            },
            {
                field: "description",
                title: "Mô tả"
            },
            {
                command: [
                    {
                        name: "edit",
                        text: "Sửa"
                    },
                    {
                        name: "destroy",
                        text: "Xoá"
                    }
                ],
                title: "&nbsp;",
                width: 200,
                attributes: {
                    style: "padding-left: 20px;"
                }
            }
        ],
        editable: "inline",
        sortable: true,
        toolbar: [
            {
                name: "create",
                text: "Thêm nhóm mới"
            }
        ],
        resizable: true,
        dataSource: {
            transport: {
                read: {
                    url: "http://localhost/BLX.VN/data/user-group.json",
                    dataType: "json",
                    type: "GET"
                },
                create: {
                    url: "http://localhost/BLX.VN/data/user-group.json",
                    dataType: "json",
                    type: "POST"
                },
                update: {
                    url: "http://localhost/BLX.VN/data/user-group.json",
                    dataType: "json",
                    type: "POST"
                },
                destroy: {
                    url: "http://localhost/BLX.VN/data/user-group.json",
                    dataType: "json",
                    type: "POST"
                }
            },
            schema: {
                total: "total",
                data: function (response) {
                    return response.groups || [];
                },
                model: {
                    id: "ID",
                    fields: {
                        ID: {
                            editable: false,
                            type: "number"
                        },
                        title: {
                            type: "string",
                            nullable: false,
                            validation: {
                                required: true
                            }
                        },
                        description: {
                            type: "string"
                        },
                        memberCount: {
                            type: "number",
                            editable: false
                        }
                    }
                }
            }
        },
        dataBound: function () {
            // Manipulate toolbar
            $(".k-grid-toolbar").css("overflow","hidden").prepend('<h1 class="pull-left" style="font-size: 18px;color: #3b5998;margin: 0;padding: 0;line-height: 28px; font-weight: normal">Danh sách nhóm thành viên</h1>');
            $(".k-grid-toolbar .k-grid-add").addClass("pull-right");
        }
    });
});