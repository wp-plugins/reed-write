<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
	<h2>More Content <a href="<?php echo get_bloginfo('url'); ?>/wp-admin/post-new.php?post_type=content-type" class="button add-new-h2">Add New</a> </h2>
	<ul class="subsubsub">
		<li class="all"><a href="edit.php?post_type=post" class="current">All <span class="count">(1)</span></a> |</li>
		<li class="publish"><a href="edit.php?post_status=publish&amp;post_type=post">Published <span class="count">(1)</span></a></li>
	</ul>
	<form id="posts-filter" action="" method="get">
		<p class="search-box">
			<label class="screen-reader-text" for="post-search-input">Search Posts:</label>
			<input type="text" id="post-search-input" name="s" value="">
			<input type="submit" name="" id="search-submit" class="button" value="Search Posts">
		</p>
		<input type="hidden" name="post_status" class="post_status_page" value="all">
		<input type="hidden" name="post_type" class="post_type_page" value="post">
		<input type="hidden" id="_wpnonce" name="_wpnonce" value="75ac9b81cc">
		<input type="hidden" name="_wp_http_referer" value="/wp-admin/edit.php">
		<div class="tablenav top">
			<div class="alignleft actions">
				<select name="action">
					<option value="-1" selected="selected">Bulk Actions</option>
					<option value="edit">Edit</option>
					<option value="trash">Move to Trash</option>
				</select>
				<input type="submit" name="" id="doaction" class="button-secondary action" value="Apply">
			</div>
			<div class="alignleft actions">
				<select name="m">
					<option selected="selected" value="0">Show all dates</option>
					<option value="201104">April 2011</option>
					<option value="201103">March 2011</option>
				</select>
				<select name="cat" id="cat" class="postform">
					<option value="0">View all categories</option>
					<option class="level-0" value="1">Uncategorized</option>
				</select>
				<input type="submit" name="" id="post-query-submit" class="button-secondary" value="Filter">
			</div>
			<div class="tablenav-pages one-page"><span class="displaying-num">1 item</span> <a class="first-page disabled" title="Go to the first page" href="http://test.scottreeddesign.com/wp-admin/edit.php">«</a> <a class="prev-page disabled" title="Go to the previous page" href="http://test.scottreeddesign.com/wp-admin/edit.php?paged=1">‹</a> <span class="paging-input">
				<input class="current-page" title="Current page" type="text" name="paged" value="1" size="1">
				of <span class="total-pages">1</span></span> <a class="next-page disabled" title="Go to the next page" href="http://test.scottreeddesign.com/wp-admin/edit.php?paged=1">›</a> <a class="last-page disabled" title="Go to the last page" href="http://test.scottreeddesign.com/wp-admin/edit.php?paged=1">»</a></div>
			<input type="hidden" name="mode" value="list">
			<div class="view-switch"> <a href="/wp-admin/edit.php?mode=list" class="current"><img id="view-switch-list" src="http://test.scottreeddesign.com/wp-includes/images/blank.gif" width="20" height="20" title="List View" alt="List View"></a> <a href="/wp-admin/edit.php?mode=excerpt"><img id="view-switch-excerpt" src="http://test.scottreeddesign.com/wp-includes/images/blank.gif" width="20" height="20" title="Excerpt View" alt="Excerpt View"></a> </div>
			<br class="clear">
		</div>
		<table class="wp-list-table widefat fixed posts" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox"></th>
					<th scope="col" id="title" class="manage-column column-title sortable desc" style=""><a href="http://test.scottreeddesign.com/wp-admin/edit.php?orderby=title&amp;order=asc"><span>Title</span><span class="sorting-indicator"></span></a></th>
					<th scope="col" id="author" class="manage-column column-author sortable desc" style=""><a href="http://test.scottreeddesign.com/wp-admin/edit.php?orderby=author&amp;order=asc"><span>Author</span><span class="sorting-indicator"></span></a></th>
					<th scope="col" id="categories" class="manage-column column-categories" style="">Categories</th>
					<th scope="col" id="tags" class="manage-column column-tags" style="">Tags</th>
					<th scope="col" id="comments" class="manage-column column-comments num sortable desc" style=""><a href="http://test.scottreeddesign.com/wp-admin/edit.php?orderby=comment_count&amp;order=asc"><span>
						<div class="vers"><img alt="Comments" src="http://test.scottreeddesign.com/wp-admin/images/comment-grey-bubble.png"></div>
						</span><span class="sorting-indicator"></span></a></th>
					<th scope="col" id="date" class="manage-column column-date sortable asc" style=""><a href="http://test.scottreeddesign.com/wp-admin/edit.php?orderby=date&amp;order=desc"><span>Date</span><span class="sorting-indicator"></span></a></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col" class="manage-column column-cb check-column" style=""><input type="checkbox"></th>
					<th scope="col" class="manage-column column-title sortable desc" style=""><a href="http://test.scottreeddesign.com/wp-admin/edit.php?orderby=title&amp;order=asc"><span>Title</span><span class="sorting-indicator"></span></a></th>
					<th scope="col" class="manage-column column-author sortable desc" style=""><a href="http://test.scottreeddesign.com/wp-admin/edit.php?orderby=author&amp;order=asc"><span>Author</span><span class="sorting-indicator"></span></a></th>
					<th scope="col" class="manage-column column-categories" style="">Categories</th>
					<th scope="col" class="manage-column column-tags" style="">Tags</th>
					<th scope="col" class="manage-column column-comments num sortable desc" style=""><a href="http://test.scottreeddesign.com/wp-admin/edit.php?orderby=comment_count&amp;order=asc"><span>
						<div class="vers"><img alt="Comments" src="http://test.scottreeddesign.com/wp-admin/images/comment-grey-bubble.png"></div>
						</span><span class="sorting-indicator"></span></a></th>
					<th scope="col" class="manage-column column-date sortable asc" style=""><a href="http://test.scottreeddesign.com/wp-admin/edit.php?orderby=date&amp;order=desc"><span>Date</span><span class="sorting-indicator"></span></a></th>
				</tr>
			</tfoot>
			<tbody id="the-list">
				<tr id="post-1" class="alternate author-self status-publish format-default iedit" valign="top">
					<th scope="row" class="check-column"><input type="checkbox" name="post[]" value="1"></th>
					<td class="post-title page-title column-title"><strong><a class="row-title" href="http://test.scottreeddesign.com/wp-admin/post.php?post=1&amp;action=edit" title="Edit "Hello world!"">Hello world!</a></strong>
						<div class="row-actions"><span class="edit"><a href="http://test.scottreeddesign.com/wp-admin/post.php?post=1&amp;action=edit" title="Edit this item">Edit</a> | </span><span class="inline hide-if-no-js"><a href="#" class="editinline" title="Edit this item inline">Quick&nbsp;Edit</a> | </span><span class="trash"><a class="submitdelete" title="Move this item to the Trash" href="http://test.scottreeddesign.com/wp-admin/post.php?post=1&amp;action=trash&amp;_wpnonce=77711c2422">Trash</a> | </span><span class="view"><a href="http://test.scottreeddesign.com/posts/1" title="View "Hello world!"" rel="permalink">View</a></span></div>
						<div class="hidden" id="inline_1">
							<div class="post_title">Hello world!</div>
							<div class="post_name">hello-world</div>
							<div class="post_author">1</div>
							<div class="comment_status">open</div>
							<div class="ping_status">open</div>
							<div class="_status">publish</div>
							<div class="jj">04</div>
							<div class="mm">03</div>
							<div class="aa">2011</div>
							<div class="hh">04</div>
							<div class="mn">34</div>
							<div class="ss">11</div>
							<div class="post_password"></div>
							<div class="post_category" id="category_1">1</div>
							<div class="tags_input" id="post_tag_1"></div>
							<div class="sticky"></div>
						</div></td>
					<td class="author column-author"><a href="edit.php?post_type=post&amp;author=1">admin</a></td>
					<td class="categories column-categories"><a href="edit.php?post_type=post&amp;category_name=uncategorized">Uncategorized</a></td>
					<td class="tags column-tags">No Tags</td>
					<td class="comments column-comments"><div class="post-com-count-wrapper"> <a href="http://test.scottreeddesign.com/wp-admin/edit-comments.php?p=1" title="0 pending" class="post-com-count"><span class="comment-count">1</span></a> </div></td>
					<td class="date column-date"><abbr title="2011/03/04 4:34:11 AM">2011/03/04</abbr><br>
						Published</td>
				</tr>
			</tbody>
		</table>
		<div class="tablenav bottom">
			<div class="alignleft actions">
				<select name="action2">
					<option value="-1" selected="selected">Bulk Actions</option>
					<option value="edit">Edit</option>
					<option value="trash">Move to Trash</option>
				</select>
				<input type="submit" name="" id="doaction2" class="button-secondary action" value="Apply">
			</div>
			<div class="alignleft actions"> </div>
			<div class="tablenav-pages one-page"><span class="displaying-num">1 item</span> <a class="first-page disabled" title="Go to the first page" href="http://test.scottreeddesign.com/wp-admin/edit.php">«</a> <a class="prev-page disabled" title="Go to the previous page" href="http://test.scottreeddesign.com/wp-admin/edit.php?paged=1">‹</a> <span class="paging-input">1 of <span class="total-pages">1</span></span> <a class="next-page disabled" title="Go to the next page" href="http://test.scottreeddesign.com/wp-admin/edit.php?paged=1">›</a> <a class="last-page disabled" title="Go to the last page" href="http://test.scottreeddesign.com/wp-admin/edit.php?paged=1">»</a></div>
			<br class="clear">
		</div>
	</form>
	<form method="get" action="">
		<table style="display: none">
			<tbody id="inlineedit">
				<tr id="inline-edit" class="inline-edit-row inline-edit-row-post inline-edit-post quick-edit-row quick-edit-row-post inline-edit-post" style="display: none">
					<td colspan="7" class="colspanchange"><fieldset class="inline-edit-col-left">
							<div class="inline-edit-col">
								<h4>Quick Edit</h4>
								<label> <span class="title">Title</span> <span class="input-text-wrap">
									<input type="text" name="post_title" class="ptitle" value="">
									</span> </label>
								<label> <span class="title">Slug</span> <span class="input-text-wrap">
									<input type="text" name="post_name" value="">
									</span> </label>
								<label><span class="title">Date</span></label>
								<div class="inline-edit-date">
									<div class="timestamp-wrap">
										<select name="mm" tabindex="4">
											<option value="01">Jan</option>
											<option value="02">Feb</option>
											<option value="03" selected="selected">Mar</option>
											<option value="04">Apr</option>
											<option value="05">May</option>
											<option value="06">Jun</option>
											<option value="07">Jul</option>
											<option value="08">Aug</option>
											<option value="09">Sep</option>
											<option value="10">Oct</option>
											<option value="11">Nov</option>
											<option value="12">Dec</option>
										</select>
										<input type="text" name="jj" value="04" size="2" maxlength="2" tabindex="4" autocomplete="off">
										,
										<input type="text" name="aa" value="2011" size="4" maxlength="4" tabindex="4" autocomplete="off">
										@
										<input type="text" name="hh" value="04" size="2" maxlength="2" tabindex="4" autocomplete="off">
										:
										<input type="text" name="mn" value="34" size="2" maxlength="2" tabindex="4" autocomplete="off">
									</div>
									<input type="hidden" id="ss" name="ss" value="11">
								</div>
								<br class="clear">
								<label class="inline-edit-author"><span class="title">Author</span>
									<select name="post_author" class="authors">
										<option value="1">admin</option>
										<option value="2">arthur</option>
									</select>
								</label>
								<div class="inline-edit-group">
									<label class="alignleft"> <span class="title">Password</span> <span class="input-text-wrap">
										<input type="text" name="post_password" class="inline-edit-password-input" value="">
										</span> </label>
									<em style="margin:5px 10px 0 0" class="alignleft"> –OR– </em>
									<label class="alignleft inline-edit-private">
										<input type="checkbox" name="keep_private" value="private">
										<span class="checkbox-title">Private</span> </label>
								</div>
							</div>
						</fieldset>
						<fieldset class="inline-edit-col-center inline-edit-categories">
							<div class="inline-edit-col"> <span class="title inline-edit-categories-label">Categories <span class="catshow">[more]</span> <span class="cathide" style="display:none;">[less]</span> </span>
								<input type="hidden" name="post_category[]" value="0">
								<ul class="cat-checklist category-checklist">
									<li id="category-1" class="popular-category">
										<label class="selectit">
											<input value="1" type="checkbox" name="post_category[]" id="in-category-1">
											Uncategorized</label>
									</li>
								</ul>
							</div>
						</fieldset>
						<fieldset class="inline-edit-col-right">
							<div class="inline-edit-col">
								<label class="inline-edit-tags"> <span class="title">Post Tags</span>
									<textarea cols="22" rows="1" name="tax_input[post_tag]" class="tax_input_post_tag"></textarea>
								</label>
								<div class="inline-edit-group">
									<label class="alignleft">
										<input type="checkbox" name="comment_status" value="open">
										<span class="checkbox-title">Allow Comments</span> </label>
									<label class="alignleft">
										<input type="checkbox" name="ping_status" value="open">
										<span class="checkbox-title">Allow Pings</span> </label>
								</div>
								<div class="inline-edit-group">
									<label class="inline-edit-status alignleft"> <span class="title">Status</span>
										<select name="_status">
											<option value="publish">Published</option>
											<option value="future">Scheduled</option>
											<option value="pending">Pending Review</option>
											<option value="draft">Draft</option>
										</select>
									</label>
									<label class="alignleft">
										<input type="checkbox" name="sticky" value="sticky">
										<span class="checkbox-title">Make this post sticky</span> </label>
								</div>
							</div>
						</fieldset>
						<p class="submit inline-edit-save"> <a accesskey="c" href="#inline-edit" title="Cancel" class="button-secondary cancel alignleft">Cancel</a>
							<input type="hidden" id="_inline_edit" name="_inline_edit" value="d5d9f5d8b4">
							<a accesskey="s" href="#inline-edit" title="Update" class="button-primary save alignright">Update</a> <img class="waiting" style="display:none;" src="http://test.scottreeddesign.com/wp-admin/images/wpspin_light.gif" alt="">
							<input type="hidden" name="post_view" value="list">
							<input type="hidden" name="screen" value="edit-post">
							<br class="clear">
						</p></td>
				</tr>
				<tr id="bulk-edit" class="inline-edit-row inline-edit-row-post inline-edit-post bulk-edit-row bulk-edit-row-post bulk-edit-post" style="display: none">
					<td colspan="7" class="colspanchange"><fieldset class="inline-edit-col-left">
							<div class="inline-edit-col">
								<h4>Bulk Edit</h4>
								<div id="bulk-title-div">
									<div id="bulk-titles"></div>
								</div>
							</div>
						</fieldset>
						<fieldset class="inline-edit-col-center inline-edit-categories">
							<div class="inline-edit-col"> <span class="title inline-edit-categories-label">Categories <span class="catshow">[more]</span> <span class="cathide" style="display:none;">[less]</span> </span>
								<input type="hidden" name="post_category[]" value="0">
								<ul class="cat-checklist category-checklist">
									<li id="category-1" class="popular-category">
										<label class="selectit">
											<input value="1" type="checkbox" name="post_category[]" id="in-category-1">
											Uncategorized</label>
									</li>
								</ul>
							</div>
						</fieldset>
						<fieldset class="inline-edit-col-right">
							<label class="inline-edit-tags"> <span class="title">Post Tags</span>
								<textarea cols="22" rows="1" name="tax_input[post_tag]" class="tax_input_post_tag"></textarea>
							</label>
							<div class="inline-edit-col">
								<label class="inline-edit-author"><span class="title">Author</span>
									<select name="post_author" class="authors">
										<option value="-1">— No Change —</option>
										<option value="1">admin</option>
										<option value="2">arthur</option>
									</select>
								</label>
								<div class="inline-edit-group">
									<label class="alignleft"> <span class="title">Comments</span>
										<select name="comment_status">
											<option value="">— No Change —</option>
											<option value="open">Allow</option>
											<option value="closed">Do not allow</option>
										</select>
									</label>
									<label class="alignright"> <span class="title">Pings</span>
										<select name="ping_status">
											<option value="">— No Change —</option>
											<option value="open">Allow</option>
											<option value="closed">Do not allow</option>
										</select>
									</label>
								</div>
								<div class="inline-edit-group">
									<label class="inline-edit-status alignleft"> <span class="title">Status</span>
										<select name="_status">
											<option value="-1">— No Change —</option>
											<option value="publish">Published</option>
											<option value="private">Private</option>
											<option value="pending">Pending Review</option>
											<option value="draft">Draft</option>
										</select>
									</label>
									<label class="alignright"> <span class="title">Sticky</span>
										<select name="sticky">
											<option value="-1">— No Change —</option>
											<option value="sticky">Sticky</option>
											<option value="unsticky">Not Sticky</option>
										</select>
									</label>
								</div>
							</div>
						</fieldset>
						<p class="submit inline-edit-save"> <a accesskey="c" href="#inline-edit" title="Cancel" class="button-secondary cancel alignleft">Cancel</a>
							<input type="submit" name="bulk_edit" id="bulk_edit" class="button-primary alignright" value="Update" accesskey="s">
							<input type="hidden" name="post_view" value="list">
							<input type="hidden" name="screen" value="edit-post">
							<br class="clear">
						</p></td>
				</tr>
			</tbody>
		</table>
	</form>
	<div id="ajax-response"></div>
	<br class="clear">
</div>
