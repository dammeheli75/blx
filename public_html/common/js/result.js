$(document).ready(function () {
    var lastQuery = "";

    $("#grid").kendoGrid({
        columns: [
            {
                field: "fullName",
                title: "Họ và tên",
                width: 200
            },
            {
                field: "birthday",
                title: "Năm sinh",
                width: 100,
                attributes: {
                    style: "text-align: center"
                },
                template: function (dataItem) {
                    return kendo.toString(kendo.parseDate(dataItem.birthday, "yyyy-MM-dd"), 'dd/MM/yyyy');
                }
            },
            {
                field: "address",
                title: "Địa chỉ",
                width: 220
            },
            {
                field: "collaborator",
                title: "CTV",
                width: 103
            },
            {
                field: "result",
                title: "Kết quả",
                width: 125,
                attributes: {
                    style: "text-align: center"
                },
                template: function (dataItem) {
                    switch (dataItem.result) {
                        case 'fail_ theoretical':
                            return "Trượt lý thuyết";
                            break;
                        case 'pass':
                            return "Đạt";
                            break;
                        case 'absence':
                            return "Vắng mặt";
                            break;
                        case 'fail_practice':
                            return "Trượt thực hành";
                            break;
                        default:
                            return "<em>Chưa có</em>";
                            break;
                    }
                }
            },
            {
                field: "license",
                title: "Bằng",
                template: function (dataItem) {
                    if (dataItem.licenseFront && dataItem.licenseBack) return '<a data-toggle="tooltip" class="license" data-original-title="Click để xem ảnh lớn" data-placement="left"><img width="50" height="32" src="' + dataItem.licenseFront + '" data-original-title="Click để xem ảnh lớn" data-placement="right">&nbsp;&nbsp;<img width="50" height="32" data-toggle="tooltip" class="license" src="' + dataItem.licenseBack + '"></a>';
                    return '<em>Chưa có bằng</em>'
                },
                attributes: {
                    style: "text-align: center"
                }
            }
        ],
        dataSource: {
            transport: {
                read: baseUrl + "ket-qua-thi/read"
            },
            schema: {
                total: "total",
                data: function (response) {
                    return response.students || []
                }
            },
            serverPaging: true,
            serverFiltering: true,
            pageSize: 50
        },
        pageable: {
            messages: {
                display: "Hiển thị {0}-{1}/{2} hồ sơ"
            }
        },
        height: function () {
            return $(window).height() - 130;
        },
        dataBound: function () {
            var self = this;

            // Tooltip Activate
            $('[data-toggle="tooltip"]').tooltip();

            $('#fixture-search').find('input[name="q"]').on('change keydown paste input', function () {
                // Filter
                var q = encodeURIComponent($(this).val().trim());
                if (q != lastQuery && (q[q.length - 1] == " " || q == "")) {
                    self.dataSource.filter({
                        field: "fullName",
                        operator: "contains",
                        value: q
                    });

                    lastQuery = q;
                }
            });

            $('#fixture-search').submit(function () {
                // Filter
                var q = encodeURIComponent($(this).find('input[name="q"]').val().trim());

                if (q != lastQuery) {
                    self.dataSource.filter({
                        field: "fullName",
                        operator: "contains",
                        value: q
                    });

                    lastQuery = q;
                }

                return false;
            });

            $(".license").click(function () {
                var target = self.dataSource.getByUid($(this).parent().parent().attr('data-uid'));
                //noinspection JSJQueryEfficiency
                kendo.bind($("#license-detail-modal"), target);
                $("#license-detail-modal").modal('show');
                $('#licenseDetailTab').find('a').click(function (e) {
                    e.preventDefault();
                    $(this).tab('show');
                })
            });
        }
    });

    $(window).resize(function () {
        $("#grid").height($(window).height() - 130);
        $(".k-grid-content").height($("#grid").height() - 80);
    });
});