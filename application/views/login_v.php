<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>CMS - Arena</title>

    <!-- jQuery -->
    <script src="assets/sb-admin/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="assets/js/jquery-ui.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="assets/sb-admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/bootstrapValidator.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="assets/sb-admin/bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="assets/sb-admin/dist/js/sb-admin-2.js"></script>

    <!-- jqgrid js -->
    <script src="assets/jqgrid/js/jquery.jqGrid.min.js"></script>
    <script src="assets/ajaxFileUploader/ajaxfileupload.js"></script>
    <script src="assets/js/jquery-ui-timepicker-addon.min.js"></script>
    <script src="assets/js/jquery-ui-timepicker-ko.js"></script>
    <script src="assets/jqgrid/js/i18n/grid.locale-kr.js"></script>

    <!-- jQuery ui css -->
    <link href="assets/jqgrid/css/jquery-ui/jquery-ui.css" rel="stylesheet">

    <!-- Bootstrap Core CSS -->
    <link href="assets/sb-admin/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="assets/css/bootstrapValidator.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="assets/sb-admin/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="assets/sb-admin/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <!--link href="assets/sb-admin/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet"-->

    <!-- jqgrid CSS -->
    <link href="assets/jqgrid/css/ui.jqgrid.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Arena CMS</h3>
                    </div>
                    <div class="panel-body">
                        <form id="defaultForm" role="form" method="post" action="login/logindo">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="아이디" name="cid" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="비밀번호" name="passwd" type="password" value="">
                                </div>
                                <!--div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div-->
                                <!-- Change this to a button or input when using this as a form -->
                                <button type='submit' class="btn btn-lg btn-success btn-block">로그인</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
$(document).ready(function() {
    $('#defaultForm')
        .bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                cid: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: '아이디는 필수 입력입니다.'
                        },
                        stringLength: {
                            min: 1,
                            max: 20,
                            message: '아이디는 1자 이상 20자 미만이어야 합니다.'
                        },
                        /*remote: {
                            url: 'remote.php',
                            message: 'The username is not available'
                        },*/
                        regexp: {
                            regexp: /^[a-zA-Z0-9_]+$/,
                            message: '아이디는 영문자, 숫자로만 구성되어야 합니다.'
                        }
                    }
                },
                passwd: {
                    validators: {
                        notEmpty: {
                            message: '비밀번호는 필수 입력입니다.'
                        }
                    }
                }
            }
        })
        .on('success.form.bv', function(e) {
            // Prevent form submission
            e.preventDefault();

            // Get the form instance
            var $form = $(e.target);

            // Get the BootstrapValidator instance
            var bv = $form.data('bootstrapValidator');

            // Use Ajax to submit form data
            $.post($form.attr('action'), $form.serialize(), function(result) {
                if (result.login) {
                    location.reload();
                } else {
                    alert('아이디 또는 비밀번호가 잘못되었습니다.');
                }
            }, 'json');
        });
});
</script>
    <script type="text/javascript" src="assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
