<script>
	$(function () {
		$("#list").jqGrid({
			colNames: ['등록일', '회원 고유번호', '회원 이메일', '내용'],
			colModel: [
				{name: 'n_rdate', index: 'n_rdate', search: false},
				{
					name: 'n_u_id',
					index: 'n_u_id',
					hidden: true,
					editable: true,
					editrules: {edithidden: true, required: true}
				},
				{name: 'u_email', index: 'u_email'},
				{name: 'n_message', index: 'n_message', editable: true}
			],
			url: 'newsfeed/get_list',
			editurl: 'newsfeed/edit',
			datatype: "json",
			loadonce: false,
			rowNum: 20,
			//rowTotal: 10000,
			rowList: [20, 40, 60],
			pager: 'pager',
			toppager: true,
			cloneToTop: true,
			sortname: 'n_id',
			viewrecords: true,
			sortorder: 'DESC',
			//multiselect: true,
			caption: "뉴스피드",
			autowidth: true
			, shrinkToFit: true,
			height: 600,
			emptyrecords: '자료가 없습니다.'
			//footerrow: true,
			//userDataOnFooter: true,
			//altRows: true,
			//grouping:true,
			//groupingView: {groupField: ['payment_type']}
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
//			afterSubmit: uploadFile,
		}, {
			addCaption: '자료 추가',
			width: '600',
			recreateForm: true,
			//dataheight: 500,
			closeAfterAdd: true,
			closeOnEscape: true,
			reloadAfterSubmit: true,
//			afterSubmit: uploadFile,
			url: 'newsfeed/add'
		}, {
			closeOnEscape: true,
			url: 'newsfeed/delete',
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
			<h1 class="page-header">뉴스피드</h1>
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
