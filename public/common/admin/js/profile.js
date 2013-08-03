$(document).ready(function () {
    $("#gridOld").kendoGrid({
        columns: [
            {
                field: "ID",
                title: "ID",
                width: 60,
                attributes: {
                    style: "text-align: center"
                },
                filterable: false
            },
            {
                field: "fullName",
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
                field: "phoneNumber",
                title: "Điện thoại",
                width: 110,
                filterable: false
            },
            {
                field: "collaborator",
                title: "CTV",
                width: 110,
                filterable: false,
                template: "#= collaborator.title #",
                editor: function (container, options) {
                    $('<input data-bind="value:' + options.field + '"/>')
                        .appendTo(container)
                        .kendoDropDownList({
                            dataTextField: "title",
                            dataValueField: "value",
                            dataSource: {
                                transport: {
                                    read: "http://localhost/blx/public/administrator/collaborator/read",
                                    dataType: 'json'
                                },
                                schema: {
                                    data: "collaborators",
                                    total: "total"
                                }
                            }
                        });

                }
            },
            {
                field: "testDate",
                title: "Ngày thi",
                width: 100,
                filterable: false,
                attributes: {
                    style: "text-align: center;"
                },
                format: "{0:dd/MM/yyyy}",
                template: function (dataItem) {
                    if (dataItem.testDate) {
                        return '<a class="tooltip-cell" data-toggle="tooltip" title="' + dataItem.testVenue + '">' + kendo.toString(dataItem.testDate, "dd/MM/yyyy") + '</a>';
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
                field: "testVenue",
                title: "Địa điểm thi",
                hidden: true,
                editor: function (container, options) {
                    $('<input data-bind="value:' + options.field + '"/>')
                        .appendTo(container)
                        .kendoDropDownList({
                            dataTextField: "title",
                            dataValueField: "ID",
                            dataSource: {
                                transport: {
                                    read: {
                                        url: 'http://localhost/blx/public/administrator/venue/read',
                                        dataType: 'json'
                                    }
                                }
                            }
                        });

                },
                filterable: false
            },
            {
                field: "testStatus",
                title: "Thi",
                filterable: false,
                width: 80,
                template: function (dataItem) {
                    switch (dataItem.testStatus) {
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
                field: "licenseFront",
                title: "BLX&nbsp;<sup>trước</sup>",
                width: 76,
                filterable: false,
                attributes: {
                    style: "text-align: center;"
                },
                template: function (dataItem) {
                    if (dataItem.licenseFront) {
                        return '<img width="50" height="32" src="' + dataItem.licenseFront + '" alt="Bằng lái xe của ' + dataItem.name + '">';
                    } else {
                        return "--";
                    }
                },
                sortable: false
            },
            {
                field: "licenseBack",
                title: "BLX&nbsp;<sup>sau</sup>",
                width: 76,
                filterable: false,
                attributes: {
                    style: "text-align: center;"
                },
                template: function (dataItem) {
                    if (dataItem.licenseBack) {
                        return '<img width="50" height="32" src="' + dataItem.licenseBack + '" alt="Bằng lái xe của ' + dataItem.name + '">';
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
            return $(window).height() - 66;
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
                    url: "http://localhost/blx/public/administrator/profiles/read",
                    type: "GET"
                },
                create: {
                    url: "http://localhost/blx/public/administrator/profiles/create",
                    type: "POST"
                },
                update: {
                    url: "http://localhost/blx/public/administrator/profiles/update",
                    type: "POST"
                },
                destroy: {
                    url: "http://localhost/blx/public/administrator/profiles/destroy",
                    type: "POST"
                }
            },
            schema: {
                data: function (response) {
                    //noinspection JSUnresolvedVariable
                    return response.profiles || [];
                },
                total: "total",
                model: {
                    id: "ID",
                    fields: {
                        ID: {
                            editable: false,
                            nullable: false
                        },
                        fullName: {
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
                        phoneNumber: {
                            nullable: false,
                            validation: {
                                required: true
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

            var self = this;
            var quickSearch = $('form#quickSearch');
            quickSearch.find('input[name="q"]').on('change keydown paste input', function () {
                // Filter
                var q = $(this).val();
                if (q != lastQuickSearch) {
                    self.dataSource.filter({
                        field: "fullName",
                        operator: "contains",
                        value: q
                    });

                    lastQuickSearch = q;
                }
            });

            quickSearch.submit(function () {
                // Filter
                var q = $(this).find('input[name="q"]').val();

                if (q != lastQuickSearch) {
                    self.dataSource.filter({
                        field: "fullName",
                        operator: "contains",
                        value: q
                    });

                    lastQuickSearch = q;
                }

                return false;
            });
        }
    });

    $(".k-grid-toolbar").css("overflow", "hidden").prepend('<h1 class="pull-left" style="font-size: 18px;color: #3b5998;margin: 0;padding: 0;line-height: 28px; font-weight: normal">Danh sách hồ sơ</h1>');
    $(".k-grid-toolbar .k-grid-add").addClass("pull-right");
});