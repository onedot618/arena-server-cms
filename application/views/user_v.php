<script>
	$(function () {
		var checkDuplicate = function (postdata, formid) {
			var result, message;
			$.ajax({
				url: 'user/checkid',
				type: 'POST',
				async: false,
				dataType: 'json',
				data: {u_id: postdata.list_id, u_email: postdata.u_email},
				success: function (data, status) {
					if (data.valid) {
						result = true;
						message = 'Success.';
					} else {
						result = false;
						message = '이미 등록된 이메일 입니다.';
					}
				},
				error: function (data, status, e) {
					alert(e);
				}
			});
			return [result, message];
		}

		var uploadFile = function (response, postdata) {
			var returnData = eval("(" + response.responseText + ")");
			if (returnData.result) {
				$.ajaxFileUpload({
					url: 'user/upload/' + returnData.id,
					secureuri: false,
					fileElementId: 'u_myphoto',
					dataType: 'json',
					success: function (data, status) {
						if (data.result == true) {
						}
						else if (data.result == false) {
							alert(data.message);
							return [data.result, data.message];
						}
					},
					error: function (data, status, e) {
						alert(e);
					}
				});
			}
			$(this).jqGrid('setGridParam', {datatype: 'json'});
			return [returnData.result, returnData.message];
		}

		$("#list").jqGrid({
			colNames: ['회원번호', '가입일', '이메일', '기기번호', '계정유형', '비밀번호', 'real name', 'page name', '성별', '생일', '모바일', '프로필이미지', '국가코드'],
			colModel: [
				{name: 'u_id', index: 'u_id', search: false},
				{name: 'u_rdate', index: 'u_rdate', search: false},
				{name: 'u_email', index: 'u_email', editable: true, editrules: {required: true}},
				{name: 'u_deviceid', index: 'u_deviceid', editable: true},
				{
					name: 'u_account_type',
					index: 'u_account_type',
					stype: 'select',
					editable: true,
					edittype: 'select',
					editoptions: {value: ":;email:email;facebook:facebook"}
				},
				{name: 'u_passwd', index: 'u_passwd', editable: true, search: false},
				{name: 'u_real_name', index: 'u_real_name', editable: true},
				{name: 'u_page_name', index: 'u_page_name', editable: true},
				{
					name: 'u_sex',
					index: 'u_sex',
					stype: 'select',
					editable: true,
					edittype: 'select',
					editoptions: {value: ":;f:f;m:m"}
				},
				{name: 'u_birth', index: 'u_birth', editable: true},
				{name: 'u_phone', index: 'u_phone', editable: true},
				{name: 'u_myphoto', index: 'u_myphoto', search: false, editable: true, edittype: 'file'},
				{name: 'u_country_code', index: 'u_country_code', search: false, editable: true}
			],
			url: 'user/get_list',
			editurl: 'user/edit',
			datatype: "json",
			loadonce: false,
			rowNum: 20,
			//rowTotal: 10000,
			rowList: [20, 40, 60],
			pager: 'pager',
			toppager: true,
			cloneToTop: true,
			sortname: 'u_id',
			viewrecords: true,
			sortorder: 'desc',
			//multiselect: true,
			caption: "회원",
			autowidth: true
			, shrinkToFit: true,
			height: 600,
			emptyrecords: '자료가 없습니다.'
		});
		$("#list").jqGrid('navGrid', '#pager', {
			cloneToTop: true,
			//view: true,
			viewtext: '보기',
			edittext: '수정',
			addtext: '등록',
			deltext: '삭제',
			searchtext: '검색',
			refreshtext: '새로고침',
			search: false
		}, {
			editCaption: '자료 수정',
			width: '600', closeAfterEdit: true, closeOnEscape: true, checkOnSubmit: true,
			recreateForm: true,
			reloadAfterSubmit: true,
			//dataheight: 500,
			beforeSubmit: checkDuplicate,
			afterSubmit: uploadFile,
			beforeShowForm: function (e) {
				$('#tr_lesson').remove();
				$('#tr_subject').remove();
				$('#tr_grade').remove();
				$('#tr_topic').remove();
			}
		}, {
			addCaption: '자료 추가',
			width: '600',
			recreateForm: true,
			//dataheight: 500,
			closeAfterAdd: true,
			closeOnEscape: true,
			reloadAfterSubmit: true,
			beforeSubmit: checkDuplicate,
			afterSubmit: uploadFile,
			//beforeShowForm: function(form) { $('#tr_image', form).hide(); },
			url: 'user/add'
		}, {
			closeOnEscape: true,
			url: 'user/delete',
			afterSubmit: function (response, postdata) {
				var result = eval("(" + response.responseText + ")");
				return [result.result, result.message];
			},
		}, {
			caption: "검색",
			Find: "검색",
			Reset: "리셋",
			multipleSearch: true,
			searchOnEnter: true,
			closeOnEscape: true,
			showQuery: false,
			sopt: ['cn', 'eq']
		}, {
			width: 600,
			recreateForm: true,
		});
		$("#list").jqGrid('filterToolbar');
		// Add responsive to jqGrid
		$(window).bind('resize', function () {
			var width = $('.jqGrid_wrapper').width();
			$('#list').setGridWidth(width);
		});
	});
</script>
<style>
	.table-responsive {
		overflow-x: visible;
	}
</style>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">회원</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->

	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive" style="min-width:600px">
				<div class="jqGrid_wrapper">
					<table id='list'></table>
					<div id="pager"></div>
				</div>
			</div>
		</div>
	</div>
</div>
