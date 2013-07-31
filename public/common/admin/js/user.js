$(document).ready(function () {
    var grid = $("#grid").kendoGrid({
        columns: [
            {
                field: "ID",
                title: "ID",
                width: 60,
                attributes: {
                    style: "text-align: center;"
                },
                template: function (dataItem) {
                    return '<a class="tooltip-cell" data-toggle="tooltip" title="Tham gia: ' + dataItem.joinDate + '">' + dataItem.ID + '</a>';
                }
            },
            {
                field: "group",
                title: "Nhóm",
                width: 160,
                template: function (dataItem) {
                    //noinspection JSUnresolvedVariable
                    return dataItem.group.title;
                },
                editor: function (container, options) {
                    $('<input data-text-field="title" data-value-field="ID" data-bind="value:' + options.field + '"/>')
                        .appendTo(container)
                        .kendoDropDownList({
                            autoBind: false,
                            dataSource: {
                                transport: {
                                    read: {
                                        url: "http://localhost/BLX.VN/data/user-group.json",
                                        dataType: "json"
                                    }
                                },
                                schema: {
                                    data: function (response) {
                                        //noinspection JSUnresolvedVariable
                                        return response.groups || [];
                                    }
                                }
                            }
                        });
                }
            },
            {
                field: "fullName",
                title: "Họ và tên",
                width: 200
            },
            {
                field: "email",
                title: "Email",
                width: 200
            },
            {
                field: "password",
                title: "Mật khẩu",
                hidden: true
            },
            {
                field: "birthday",
                title: "Năm sinh",
                width: 100,
                attributes: {
                    style: "text-align: center;"
                },
                template: '#= kendo.toString(birthday,"dd/MM/yyyy") #',
                editor: function (container, options) {
                    $('<input name="birthday" style="border-radius: 0" data-bind="value:' + options.field + '"/>')
                        .appendTo(container)
                        .kendoDatePicker({
                            // defines when the calendar should return date
                            depth: "year",

                            // display month and year in the input
                            format: "dd/MM/yyyy"
                        });
                }
            },
            {
                field: "address",
                title: "Địa chỉ",
                width: 260
            },
            {
                field: "phoneNumber",
                title: "Điện thoại",
                width: 120,
                attributes: {
                    style: "text-align: center;"
                }
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
                title: "&nbsp;"
            }
        ],
        editable: "popup",
        sortable: true,
        toolbar: [
            {
                name: "create",
                text: "Thêm thành viên"
            }
        ],
        resizable: true,
        dataSource: {
            transport: {
                read: {
                    url: "http://localhost/BLX.VN/data/user.json",
                    dataType: "json",
                    type: "GET"
                },
                create: {
                    url: "http://localhost/BLX.VN/data/user.json",
                    dataType: "json",
                    type: "POST"
                },
                update: {
                    url: "http://localhost/BLX.VN/data/user.json",
                    dataType: "json",
                    type: "POST"
                },
                destroy: {
                    url: "http://localhost/BLX.VN/data/user.json",
                    dataType: "json",
                    type: "POST"
                }
            },
            schema: {
                total: "total",
                data: function (response) {
                    return response.users || [];
                },
                model: {
                    id: "ID",
                    fields: {
                        ID: {
                            editable: false,
                            type: "number"
                        },
                        group: {
                            ID: {
                                validation: {
                                    required: true
                                }
                            },
                            title: {
                                validation: {
                                    required: true
                                }
                            }
                        },
                        fullName: {
                            type: "string",
                            validation: {
                                required: true
                            }
                        },
                        birthday: {
                            type: "date",
                            validation: {
                                required: true
                            }
                        },
                        email: {
                            type: "string",
                            validation: {
                                required: true
                            }
                        },
                        password: {
                            type: "string"
                        }
                    }
                }
            }
        },
        dataBound: function () {
            // Tooltip Activate
            $('[data-toggle="tooltip"]').tooltip({
                placement: "right"
            });
        },
        dataBind: function () {

        }
    });

    // Manipulate toolbar
    $(".k-grid-toolbar").css("overflow", "hidden").prepend('<h1 class="pull-left" style="font-size: 18px;color: #3b5998;margin: 0;padding: 0;line-height: 28px; font-weight: normal">Danh sách thành viên</h1>');
    $(".k-grid-toolbar .k-grid-add").addClass("pull-right");
});