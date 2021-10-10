<!-- BEGIN: main -->
<div id="module_show_list">
	{SOURCECAT_LIST}
</div>
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<div id="content">
	<a name="edit"></a>
	<form class="source_form" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" enctype="multipart/form-data" method="post">
		<div class="row">
			<div class="form-group row clear">
				<label class="col-sm-4 control-label"><strong>{LANG.title}</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="title" type="text" id="title" class="form-control" value="{ITEM.title}"/></div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.host_url}</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="host_url" type="text" id="host_url" class="form-control" value="{ITEM.host_url}"/></div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.logo}</strong></label>
				<div class="col-lg-16 col-md-16 col-sm-16"><input name="logo" type="text" id="logo" class="form-control w500 pull-left" value="{ITEM.logo}"/><input id="select-img-cat" type="button" value="Browse server" name="selectimg" class="btn btn-info" /></div>
			</div>

			<div class="alert alert-info col-md-24 ">
				<strong>Danh sách bản tin</strong>
			</div>

			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.sample_pattern}</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="block_pattern" type="text" id="block_pattern" class="form-control" value="{ITEM.block_pattern}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.sample_title}</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="block_title" type="text" id="block_title" class="form-control" value="{ITEM.block_title}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Đối tượng xoá khỏi Tiêu đề bản tin</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="block_title_remove" type="text" id="block_title_remove" class="form-control" value="{ITEM.block_title_remove}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Nội dung xoá khỏi Tiêu đề bản tin</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="block_title_replace" type="text" id="block_title_replace" class="form-control" value="{ITEM.block_title_replace}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Liên kết đến bản tin</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="block_url" type="text" id="block_url" class="form-control" value="{ITEM.block_url}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Giới thiệu ngắn</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="block_hometext" type="text" id="block_hometext" class="form-control" value="{ITEM.block_hometext}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Đối tượng xoá khỏi Giới thiệu ngắn</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="block_hometext_remove" type="text" id="block_hometext_remove" class="form-control" value="{ITEM.block_hometext_remove}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Nội dung xoá khỏi Giới thiệu ngắn</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="block_hometext_replace" type="text" id="block_hometext_replace" class="form-control" value="{ITEM.block_hometext_replace}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Hình ảnh đại diện</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="block_thumb" type="text" id="block_thumb" class="form-control" value="{ITEM.block_thumb}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Mô tả của ảnh minh hoạ</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="block_thumb_alt" type="text" id="block_thumb_alt" class="form-control" value="{ITEM.block_thumb_alt}"/></div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Thuộc tính đường dẫn ảnh</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="block_thumb_attribute" type="text" id="block_thumb_attribute" placeholder="src" class="form-control" value="{ITEM.block_thumb_attribute}"/></div>
			</div>
			
			<div class="alert alert-info col-md-24 ">
				<strong>Chi tiết bài viết</strong>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Nội dung chi tiết</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="detail_bodyhtml" type="text" id="detail_bodyhtml" class="form-control" value="{ITEM.detail_bodyhtml}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Đối tượng xoá khỏi chi tiết</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="detail_bodyhtml_remove" type="text" id="detail_bodyhtml_remove" class="form-control" value="{ITEM.detail_bodyhtml_remove}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Nội dung xoá khỏi chi tiết</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="detail_bodyhtml_replace" type="text" id="detail_bodyhtml_replace" class="form-control" value="{ITEM.detail_bodyhtml_replace}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Thuộc tính đường dẫn ảnh chi tiết</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="detail_bodyhtml_attribute" type="text" id="detail_bodyhtml_attribute" placeholder="src" class="form-control" value="{ITEM.detail_bodyhtml_attribute}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Giới thiệu ngắn</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="detail_hometext" type="text" id="detail_hometext" class="form-control" value="{ITEM.detail_hometext}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Đối tượng xoá khỏi Giới thiệu ngắn</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="detail_hometext_remove" type="text" id="detail_hometext_remove" class="form-control" value="{ITEM.detail_hometext_remove}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Nội dung xoá khỏi Giới thiệu ngắn</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="detail_hometext_replace" type="text" id="detail_hometext_replace" class="form-control" value="{ITEM.detail_hometext_replace}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Tác giả</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="detail_author" type="text" id="detail_author" class="form-control" value="{ITEM.detail_author}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Nội dung xoá khỏi Tác giả</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="detail_author_replace" type="text" id="detail_author_replace" class="form-control" value="{ITEM.detail_author_replace}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Trạng thái kích hoạt</strong></label>
				<div class="col-lg-3 col-md-3 col-sm-3">
				<select class="form-control" id="status" name="status">
					<!-- BEGIN: status -->
					<option value="{STATUS.key}" {STATUS.selected}>{STATUS.title}</option>
					<!-- END: status -->
				</select>
				</div>
			</div>
		</div>
		<div class="text-center">
			<br/>
			<input type="hidden" value="1" name="save" />
			<input type="hidden" value="{ITEM.catid}" name="catid" />
			<input class="btn btn-primary submit-post" name="statussave" type="submit" value="{LANG.save}" />
		</div>
	</form>
</div>
<script>
var CFG = [];
CFG.upload_current = '{UPLOAD_PATH}';
CFG.upload_path = '{UPLOAD_PATH}';
</script>
<!-- END: main -->
