$(document).ready(function () {
    var profileChart = $("#profile-chart").kendoChart({
        dataSource: {
            transport: {
                read: {
                    url: "http://localhost/BLX.VN/data/chart/profile.json",
                    dataType: "json"
                }
            },
            sort: {
                field: "day",
                dir: "asc"
            }
        },
        title: {
            text: "Số hồ sơ đăng ký"
        },
        legend: {
            position: "top"
        },
        seriesDefaults: {
            type: "line"
        },
        series: [
            {
                field: "profile",
                name: "Hồ sơ",
                color: "#6d84b4"
            }
        ],
        categoryAxis: {
            field: "day"
        }
    });

    var revenueChart = $("#revenue-chart").kendoChart({
        dataSource: {
            transport: {
                read: {
                    url: "http://localhost/BLX.VN/data/chart/revenue.json",
                    dataType: "json"
                }
            },
            sort: {
                field: "week",
                dir: "asc"
            }
        },
        title: {
            text: "Doanh thu và lợi nhuận"
        },
        legend: {
            position: "top"
        },
        seriesDefaults: {
            type: "line"
        },
        series: [
            {
                field: "revenue",
                name: "Doanh thu"
            },
            {
                field: "profit",
                name: "Lợi nhuận"
            }
        ],
        categoryAxis: {
            field: "week"
        }
    });
});