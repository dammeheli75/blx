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
                field: "testDate",
                title: "Ngày thi",
                width: 115,
                attributes: {
                    style: "text-align: center"
                },
                template: function (dataItem) {
                    if (dataItem.testDate) { //noinspection JSCheckFunctionSignatures
                        return kendo.toString(kendo.parseDate(dataItem.testDate, "yyyy-MM-dd"), 'dd/MM/yyyy');
                    }
                    return '<em>Chưa xếp lịch</em>'
                }
            },
            {
                field: "venueAddress",
                title: "Địa điểm",
                template: function (dataItem) {
                    if (dataItem.testDate) return dataItem.venueAddress;
                    return ""
                }
            }
        ],
        dataSource: {
            transport: {
                read: {
                    url: "/blx/public/lich-thi/read",
                    dataType: "json"
                }
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
            $('#fixture-search').find('input[name="q"]').on('change keydown paste input', function () {
                // Filter
                var q = $(this).val();
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
                var q = $(this).find('input[name="q"]').val();

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
        }
    });

    $(window).resize(function () {
        $("#grid").height($(window).height() - 130);
        $(".k-grid-content").height($("#grid").height() - 80);
    });
});