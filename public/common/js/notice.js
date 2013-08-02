$(document).ready(function () {

    var noticeModalViewModel = new k.observable({});
    k.bind($('#noticeDetailModal'), noticeModalViewModel);

    var noticeTemplate = k.template($("#notice-template").html());
    var noMoreTemplate = k.template($("#no-more-notice").html());

    var noticeDataSource = new k.data.DataSource({
            transport: {
                read: {
                    url: "notice.php",
                    dataType: "json",
                    type: "POST",
                    data: function () {
                        return $('form').serializeArray();
                    }
                }
            },//
            schema: {
                data: function (response) {
                    //noinspection JSUnresolvedVariable
                    return response.notices || [];
                },
                total: function (response) {
                    return response.total || 0;
                }
            },
            change: function () {
                $("#notices").html(k.render(noticeTemplate, this.view()));

                if (this.total > 0) {

                } else {
                    $("#notices").html(noMoreTemplate);
                }

                $('#notices li a').click(function () {
                    var self = $(this);

                    var title = self.find('.meta h4').html();
                    var content = self.find('.full-content').html();

                    noticeModalViewModel.set('title', title);
                    noticeModalViewModel.set('content', content);

                    $('#noticeDetailModal').modal('show');
                });
            }
        })
        ;

    $('#filter-notice-form input').change(function () {
        noticeDataSource.read();
    });

    $('#filter-notice-form').submit(function () {
        noticeDataSource.read();
    });

    noticeDataSource.read();
})
;