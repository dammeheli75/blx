$(document).ready(function () {
    $("#grid").kendoGrid({
        columns: [
            {
                field: "profileID",
                title: "ID",
                width: 60,
                attributes: {
                    style: "text-align: center"
                },
                filterable: false
            },
            {
                field: "name",
                title: "Họ và tên",
                width: 180,
                template: function (dataItem) {
                    return '<a class="tooltip-cell" data-toggle="tooltip" title="Năm sinh: ' + kendo.toString(dataItem.birthday, "dd/MM/yyyy") + '">' + dataItem.name + '</a>';
                },
                filterable: false
            },
            {
                field: "birthday",
                title: "Năm sinh",
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
                },
                hidden: true,
                filterable: false
            },
            {
                field: "address",
                title: "Địa chỉ",
                width: 260,
                filterable: false
            },
            {
                field: "phone.number",
                title: "Điện thoại",
                width: 110,
                template: "#= phone.number #",
                filterable: false
            },
            {
                field: "collaborator",
                title: "CTV",
                width: 110,
                filterable: false
            },
            {
                field: "test.date",
                title: "Ngày thi",
                width: 100,
                filterable: false,
                attributes: {
                    style: "text-align: center;"
                },
                format: "{0:dd/MM/yyyy}",
                template: function (dataItem) {
                    if (dataItem.test.date) {
                        return '<a class="tooltip-cell" data-toggle="tooltip" title="' + dataItem.test.venue + '">' + kendo.toString(dataItem.test.date, "dd/MM/yyyy") + '</a>';
                    } else {
                        return '--';
                    }

                },
                editor: function (container, options) {
                    $('<input style="border-radius: 0" data-bind="value:' + options.field + '"/>')
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
                field: "test.venue",
                title: "Địa điểm thi",
                hidden: true,
                editor: function (container, options) {
                    $('<input data-bind="value:' + options.field + '"/>')
                        .appendTo(container)
                        .kendoDropDownList({
                            dataTextField: "title",
                            dataValueField: "value",
                            dataSource: [
                                {
                                    title: "Chọn địa điểm",
                                    value: null
                                },
                                {
                                    title: "Số 1 Quốc Tử Giám",
                                    value: 1
                                },
                                {
                                    title: "101 Tô Vĩnh Diện",
                                    value: 2
                                }
                            ]
                        });

                },
                filterable: false
            },
            {
                field: "test.status",
                title: "Thi",
                filterable: false,
                width: 80,
                template: function (dataItem) {
                    switch (dataItem.test.status) {
                        case 0:
                            return "--";
                            break;
                        case 1:
                            return "Trượt LT";
                            break;
                        case 2:
                            return "Đạt";
                            break;
                        case 3:
                            return "Vắng mặt";
                            break;
                        case 4:
                            return "Trượt TH";
                            break;
                        default:
                            return "--";
                            break;
                    }
                },
                editor: function (container, options) {
                    $('<input data-bind="value:' + options.field + '"/>')
                        .appendTo(container)
                        .kendoDropDownList({
                            dataTextField: "title",
                            dataValueField: "value",
                            dataSource: [
                                {
                                    title: "Kết quả thi",
                                    value: 0
                                },
                                {
                                    title: "Trượt lý thuyết",
                                    value: 1
                                },
                                {
                                    title: "Đạt",
                                    value: 2
                                },
                                {
                                    title: "Vắng mặt",
                                    value: 3
                                },
                                {
                                    title: "Trượt thực hành",
                                    value: 4
                                }

                            ]
                        });

                }
            },
            {
                field: "license.front",
                title: "BLX&nbsp;<sup>trước</sup>",
                width: 76,
                filterable: false,
                attributes: {
                    style: "text-align: center;"
                },
                template: function (dataItem) {
                    if (dataItem.license.front) {
                        return '<img width="50" height="32" src="' + dataItem.license.front + '" alt="Bằng lái xe của ' + dataItem.name + '">';
                    } else {
                        return "--";
                    }
                },
                sortable: false
            },
            {
                field: "license.back",
                title: "BLX&nbsp;<sup>sau</sup>",
                width: 76,
                filterable: false,
                attributes: {
                    style: "text-align: center;"
                },
                template: function (dataItem) {
                    if (dataItem.license.back) {
                        return '<img width="50" height="32" src="' + dataItem.license.back + '" alt="Bằng lái xe của ' + dataItem.name + '">';
                    } else {
                        return "--";
                    }
                },
                sortable: false
            },
            {
                field: "note",
                title: "Ghi chú",
                filterable: false,
                sortable: false
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
                width: 160,
                attributes: {
                    style: "padding-left: 15px"
                }
            }
        ],
        height: function () {
            return ($(window).height() - 66) + "px";
        },
        pageable: {
            pageSize: 50,
            messages: {
                display: "Hiển thị {0}-{1}/{2} hồ sơ"
            }
        },
        sortable: true,
        resizable: true,
        filterable: {
            extra: false,
            messages: {
                info: "Điều kiện lọc: ",
                filter: "Lọc",
                clear: "Xoá",
                isFalse: "False",
                isTrue: "True"
            },
            operators: {
                string: {
                    contains: "Có chứa"
                },
                date: {
                    gt: "Sau ngày",
                    lt: "Trước ngày",
                    eq: "Là ngày"
                }
            }
        },
        editable: {
            mode: "popup",
            confirmation: "Có chắc chắn muốn xoá?"
        },
        toolbar: [
            {
                name: "create",
                text: "Thêm hồ sơ"
            }
        ],
        dataSource: {
            transport: {
                read: {
                    url: "http://localhost/BLX.VN/data/profile.json",
                    type: "GET",
                    dataType: "json"
                },
                create: {
                    url: "http://localhost/BLX.VN/profile-create.php",
                    type: "POST",
                    dataType: "json"
                },
                update: {
                    url: "http://localhost/BLX.VN/profile-update.php",
                    type: "POST",
                    dataType: "json"
                },
                destroy: {
                    url: "http://localhost/BLX.VN/profile-destroy.php",
                    type: "POST",
                    dataType: "json"
                }
            },
            schema: {
                data: function (response) {
                    //noinspection JSUnresolvedVariable
                    return response.profiles || [];
                },
                total: "total",
                model: {
                    id: "profileID",
                    fields: {
                        profileID: {
                            editable: false,
                            nullable: false
                        },
                        name: {
                            nullable: false,
                            validation: {
                                required: true
                            }
                        },
                        birthday: {
                            nullable: false,
                            validation: {
                                required: true
                            }
                        },
                        address: {
                            filterable: false,
                            nullable: false,
                            validation: {
                                required: true
                            }
                        },
                        phone: {
                            number: {
                                nullable: false,
                                validation: {
                                    required: true
                                }
                            }
                        },
                        test: {
                            status: {
                                nullable: true
                            },
                            venue: {
                                nullable: true
                            },
                            date: {
                                nullable: true
                            }
                        },
                        collaborator: {
                            type: "string"
                        },
                        license: {
                            front: {
                                type: "string"
                            },
                            back: {
                                type: "string"
                            }
                        },
                        note: {
                            type: "string"
                        },
                        time_created: {
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
        }
    });

    $(".k-grid-toolbar").css("overflow", "hidden").prepend('<h1 class="pull-left" style="font-size: 18px;color: #3b5998;margin: 0;padding: 0;line-height: 28px; font-weight: normal">Danh sách hồ sơ</h1>');
    $(".k-grid-toolbar .k-grid-add").addClass("pull-right");
});