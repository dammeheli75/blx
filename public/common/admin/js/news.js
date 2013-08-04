$(document).ready(function () {
    $("#grid").kendoGrid({
        columns: [
            {
                field: "ID",
                title: "#",
                width: 60,
                sortable: false,
                attributes: {
                    style: "text-align: center"
                }
            },
            {
                field: "title",
                title: "Tiêu đề",
                width: $(window).width() - 780,
                template: "<a class='post-title'>#= title #</a>"
            },
            {
                field: "author",
                title: "Tác giả",
                width: 140,
                sortable: false,
                template: "<a class='author-name'>#= author.fullName #</a>",
                attributes: {
                    style: "text-align: center"
                }
            },
            {
                field: "category",
                title: "Chuyên mục",
                width: 180,
                sortable: false,
                template: "<a class='category-title'>#= category.title #</a>"
            },
            {
                field: "status", // "published" | "pending_review" | "draft"
                title: "Trạng thái",
                width: 100,
                sortable: false,
                attributes: {
                    style: "text-align: center"
                },
                template: function (dataItem) {
                    var status = dataItem.status;
                    if (status) {
                        switch (status) {
                            case "published":
                                return "Đã xuất bản";
                            case "pending_review":
                                return "Chờ xem lại";
                            default:
                                return "Bản nháp";
                        }
                    } else {
                        return "--";
                    }
                }
            },
            {
                field: "postDate",
                title: "Ngày đăng",
                width: 100,
                sortable: false,
                attributes: {
                    style: "text-align: center"
                },
                template: '#= kendo.toString(postDate,"dd/MM/yyyy") #'
            },
            {
                field: "lastUpdate",
                title: "Sửa lần cuối",
                width: 100,
                sortable: false,
                attributes: {
                    style: "text-align: center"
                },
                template: '#= kendo.toString(postDate,"dd/MM/yyyy") #'
            },
            {
                "title": "&nbsp;",
                "command": [
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
                    url: "http://localhost/blx/public/prototype/data/posts.json",
                    dataType: "json"
                }
            },
            schema: {
                data: "posts",
                total: "total",
                model: {
                    id: "ID",
                    fields: {
                        postDate: {
                            type: "date"
                        },
                        lastUpdate: {
                            type: "date"
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
                name: "add",
                template: kendo.template($("#addButtonTemplate").html())
            },
            {
                name: "search",
                template: kendo.template($("#quickSearchPostTemplate").html())
            }
        ],
        sortable: true,
        resizable: true,
        pageable: {
            pageSize: 50,
            messages: {
                display: "Hiển thị {0}-{1}/{2} bài viết",
                empty: "Không có bài viết nào"
            }
        },
        editable: {
            create: false,
            update: false,
            confirmation: "Chú ý: bài viết đã xoá sẽ không lấy lại được. Có chắc chắn muốn xoá bài viết này?"
        }
    });
});

$('.k-grid-toolbar').ready(function () {
    $(".k-grid-toolbar").css("overflow", "hidden").prepend('<h1 class="pull-left" style="font-size: 18px;color: #3b5998;margin: 0;padding: 0;line-height: 28px; font-weight: normal">Quản lý bài viết</h1>');
});