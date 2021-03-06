$(document).ready(function () {
    $("#gridOld").kendoGrid({
        columns: [
            {
                field: "ID",
                title: "#",
                width: 80,
                attributes: {
                    style: "text-align: center"
                },
                filterable: false
            },
            {
                field: "title",
                title: "Tên chuyên mục",
                width: 320
            },
            {
                field: "description",
                title: "Mô tả",
                width: 360,
                filterable: false
            },
            {
                field: "slug",
                title: "Slug",
                width: 240,
                filterable: false,
                editor: function (container, options) {
                    $('<input type="text" class="k-input k-textbox" name="slug" placeholder="Để trống để tự tạo" data-bind="value:' + options.field + '"/>')
                        .appendTo(container);
                }
            },
            {
                field: "postCount",
                title: "Số bài viết",
                width: 120,
                filterable: false,
                attributes: {
                    style: "text-align: center"
                }
            },
            {
                "title": "&nbsp;",
                "command": [
                    {
                        "name": "edit",
                        "text": "Sửa"
                    },
                    {
                        "name": "destroy",
                        "text": "Xoá"
                    }
                ]
            }
        ],
        dataSource: {
            transport: {
                read: {
                    url: "/blx/public/administrator/categories/read",
                    type: "GET",
                    dataType: "json"
                },
                create: {
                    url: "/blx/public/administrator/categories/create",
                    type: "POST"
                },
                update: {
                    url: "/blx/public/administrator/categories/update",
                    type: "POST"
                },
                destroy: {
                    url: "/blx/public/administrator/categories/destroy",
                    type: "POST"
                }
            },
            schema: {
                data: "categories",
                total: "total",
                model: {
                    id: "ID",
                    fields: {
                        ID: {
                            editable: false,
                            type: "number",
                            defaultValue: null
                        },
                        title: {
                            nullable: false,
                            required: true
                        },
                        description: {
                            type: "string"
                        },
                        slug: {
                            type: "string"
                        },
                        postCount: {
                            type: "number",
                            editable: false,
                            required: true,
                            defaultValue: 0
                        }
                    }
                }
            }
        },
        height: function () {
            return $(window).height() - 66;
        },
        toolbar: [
            {
                name: "create",
                text: "Tạo chuyên mục mới"
            }
        ],
        pageable: {
            pageSize: 10,
            messages: {
                display: "Hiển thị {0}-{1}/{2} chuyên mục",
                empty: "Không có chuyên mục nào"
            }
        },
        sortable: true,
        filterable: {
            extra: false,
            messages: {
                info: "Lọc theo: ",
                and: "and",
                or: "or",
                filter: "Lọc",
                clear: "Xoá"
            },
            operators: {
                string: {
                    contains: "Có chứa"
                }
            }
        },
        editable: {
            mode: "popup",
            confirmation: "Có chắc chắn muốn xoá chuyên mục này?"
        }
    });
});

$('.k-grid-toolbar').ready(function () {
    $(".k-grid-toolbar").css("overflow", "hidden").prepend('<h1 class="pull-left" style="font-size: 18px;color: #3b5998;margin: 0;padding: 0;line-height: 28px; font-weight: normal">Quản lý chuyên mục</h1>');
    $(".k-grid-toolbar .k-grid-add").addClass("pull-right");
});