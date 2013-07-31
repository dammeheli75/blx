$(document).ready(function () {
    var flag = {
        SUCCESS: 1,
        ERROR: 2,
        NOTICE: 3
    };

    $(window).resize(function () {
        $("#grid").height(($(window).height() - 66) + "px");
        $(".k-grid-content").height($("#grid").height() - 109);
        $(".footer-toolbar").width($(window).width() - $(".k-grid-pager").width() - 30);
    });

    $('#grid').kendoGrid({
        columns: [
            {
                field: "flag",
                title: "&nbsp;",
                width: "8px",
                template: function (dataItem) {
                    //noinspection JSUnresolvedVariable
                    switch (dataItem.flag) {
                        case flag.SUCCESS:
                            return "<span class='flag flag-success'></span>";
                        case flag.ERROR:
                            return "<span class='flag flag-error'></span>";
                        case flag.NOTICE:
                            return "<span class='flag flag-notice'></span>";
                        default:
                            return "<span class='flag'></span>";
                    }
                },
                attributes: {
                    style: "padding: 0;"
                }
            },
            {
                field: "profile_id",
                title: "ID",
                width: "60px",
                attributes: {
                    style: "text-align: center;"
                }
            },
            {
                field: "name",
                title: "Họ và tên",
                width: "180px"
            },
            {
                field: "address",
                title: "Địa chỉ",
                width: "300px"
            },
            {
                field: "phone_number",
                title: "Điện thoại",
                width: "145px",
                template: function (dataItem) {
                    if (dataItem.onlySMS) {
                        return dataItem.phone_number + "&nbsp;<sup>SMS</sup>";
                    }
                    return dataItem.phone_number;
                },
                attributes: {
                    style: "text-align: center;"
                }
            },
            {
                field: "contact",
                title: "Liên hệ",
                width: "80px",
                attributes: {
                    style: "text-align: center;"
                },
                template: function (dataItem) {
                    if (dataItem.contact) {
                        return "Rồi";
                    } else {
                        return "Chưa";
                    }
                },
                editor: function (container, options) {
                    $('<input data-text-field="selectionTitle" data-value-field="selectionValue" data-bind="value:' + options.field + '"/>')
                        .appendTo(container)
                        .kendoDropDownList({
                            autoBind: false,
                            dataSource: [
                                {
                                    selectionTitle: "Rồi",
                                    selectionValue: true
                                },
                                {
                                    selectionTitle: "Chưa",
                                    selectionValue: false
                                }
                            ]
                        });
                }

            },
            {
                field: "payment",
                title: "Nộp tiền",
                width: "80px",
                attributes: {
                    style: "text-align: center;"
                },
                template: function (dataItem) {
                    if (dataItem.contact) {
                        return "Rồi";
                    } else {
                        return "Chưa";
                    }
                },
                editor: function (container, options) {
                    $('<input data-text-field="selectionTitle" data-value-field="selectionValue" data-bind="value:' + options.field + '"/>')
                        .appendTo(container)
                        .kendoDropDownList({
                            autoBind: false,
                            dataSource: [
                                {
                                    selectionTitle: "Rồi",
                                    selectionValue: true
                                },
                                {
                                    selectionTitle: "Chưa",
                                    selectionValue: false
                                }
                            ]
                        });
                }
            },
            {
                field: "charge",
                title: "Phụ trách",
                width: "140px",
                template: "#= charge.collaboratorName #",
                editor: function (container, options) {
                    $('<input data-text-field="collaboratorName" data-value-field="collaboratorID" data-bind="value:' + options.field + '"/>')
                        .appendTo(container)
                        .kendoDropDownList({
                            autoBind: false,
                            dataSource: [
                                {
                                    collaboratorName: "Việt Dũng",
                                    collaboratorID: "1"
                                },
                                {
                                    collaboratorName: "Hoàng Nam",
                                    collaboratorID: "2"
                                }
                            ]
                        });
                }
            },
            {
                field: "note",
                title: "Ghi chú"
            }
        ],
        height: function () {
            return ($(window).height() - 66) + "px";
        },
        pageable: {
            info: false,
            pageSize: 10
        },
        sortable: true,
        editable: true,
        dataSource: {
            transport: {
                read: {
                    url: "http://localhost/BLX.VN/profile-approval.php",
                    type: "GET",
                    dataType: "json"
                },
                update: {
                    url: "http://localhost/BLX.VN/profile-approval-update.php",
                    type: "POST",
                    dataType: "json"
                },
                destroy: {
                    url: "http://localhost/BLX.VN/profile-approval-destroy.php",
                    type: "POST",
                    dataType: "json"
                }
            },
            schema: {
                data: function (response) {
                    return response.profiles || [];
                },
                total: "total",
                model: {
                    id: "profile_id",
                    fields: {
                        profile_id: {
                            editable: false,
                            nullable: false,
                            validation: {
                                required: true
                            }
                        },
                        name: {
                            editable: false,
                            nullable: false,
                            validation: {
                                required: true
                            }
                        },
                        address: {
                            editable: false,
                            nullable: false,
                            validation: {
                                required: true
                            }
                        },
                        phone_number: {
                            editable: false,
                            nullable: false,
                            validation: {
                                required: true
                            }
                        },
                        contact: {
                            nullable: false,
                            type: "Boolean",
                            defaultValue: false,
                            validation: {
                                required: true
                            }
                        },
                        payment: {
                            nullable: false,
                            type: "Boolean",
                            defaultValue: false,
                            validation: {
                                required: true
                            }
                        }
                    }

                }
            },
            autoSync: true
        },
        toolbar: kendo.template($("#toolbarTemplate").html()),
        //
        // Events
        //
        dataBound: function () {

            // Prepare footer toolbar
            $("#grid").append(kendo.template($("#footerToolbarTemplate").html()));
            $(".footer-toolbar").width($(window).width() - $(".k-grid-pager").width() - 30);
            $(".footer-toolbar").height($(".k-grid-pager").height() + 16);
        },
        edit: function (e) {

//            var editRow = e.container.parent();
//            var editTarget = e.model;
//
//            if (editTarget.payment == editTarget.contact == true) {
//                editRow.addClass("approved-row");
//            }
        }
    });
});