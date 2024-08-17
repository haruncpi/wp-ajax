<?php
/**
 * Ajax inspector main view file
 *
 * @package Ajax
 */

?>

<div class="ajax-inspector" ng-app="ajaxApp" ng-controller="AppCtrl" ng-cloak>
	<div class="left-right-content header">
		<div class="display-flex align-items-center">
			<h1>Ajax Inspector</h1>
			<p>v<?php echo esc_html( AJAX_VERSION ); ?></p>
		</div>

		<div class="display-flex align-items-center gap-10" ng-show="pluginInfo.updateAvailable">
			<span style="color: red;" ng-hide="updating">New version available - <strong>v{{pluginInfo.newVersion}}</strong></span>

			<button type="button" ng-hide="updating" class="button button-default" ng-click="updatePlugin()">{{updating? 'Updating':'Update Now'}}</button>

			<span class="updating-info" ng-show="updating">
				<span class="dashicons dashicons-update"></span>	
				Updating version from <strong>{{pluginInfo.currentVersion}}</strong> to <strong>{{pluginInfo.newVersion}}</strong>
			</span>
		</div>
	</div>

	<?php add_thickbox(); ?>
	<div id="param-info-thickbox" style="display:none;">
		<p style="margin-bottom: 0;">Key value pair with (:) separated.
		<br>
		<br>
		<strong>Example</strong>
		<br>
<pre style="margin-top: 0;">
name:jhon
nage:25
city:dhaka
email:jhon@example.com
hobbies[]:drawing
hobbies[]:swimming
hobbies[]:cricket
</pre>
		</p>
	</div>

	<div class="body-wrapper">
		<input type="hidden" class="ajax_url" value="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
		<div class="input-wrapper">

			<div class="ajax-actions">
				<div class="display-flex align-items-center gap-10">
					<strong>
						<a style="color: #777;" href="#TB_inline?width=300&height=220&inlineId=param-info-thickbox" title="Params" class="dashicons dashicons-info-outline thickbox"></a> 
						Params</strong>
				</div>
				<div>
					<span class="selected-title" ng-show="model.id"><strong>Selected</strong>: {{model.title}}</span>
					<button ng-disabled="saving || !model.payload.length" ng-click="save(model)" class="button button-default">{{saving?'Saving': model.id ? 'Update':'Save'}}</button>
					<button ng-disabled="sending || !model.payload.length" class="button button-primary" id="ajax">{{sending?'Sending':'Send'}}</button>
				</div>
			</div>

			<div>
				<textarea id="input" ng-model="model.payload" rows="4"></textarea>
			</div>

			<div class="saved-request-list-wrapper" ng-show="savedList.length">

				<div class="wp-filter">
					<ul class="filter-links">
						<li class="plugin-install-featured">
							<a href="#" class="current" aria-current="page">Saved Request ({{savedList.length}})</a> 
						</li>
					</ul>

					<form class="search-form search-list" method="get">
						<input type="search" ng-model="s" name="s" id="search-list"  class="wp-filter-search" placeholder="Search list..." autocomplete="off">
						<input type="submit" id="search-submit" class="button hide-if-js" value="Search list">	
					</form>
				</div>


				<table class="wp-list-table widefat fixed table-view-list">
					<tr ng-repeat="row in savedList|filter:{ title: s }" ng-class="row.id===model.id? 'row-selected':''">
						<td ng-click="toggleSelect(row)" class="title-column">{{row.title}}</td>
						<td class="ajax-list-action">
							<a class="row-delete" href="#" ng-click="remove(row)">Delete</a>
						</td>
					</tr>
				</table>
			</div>

		</div>
		<div class="response-wrapper">
			<p><strong>Response</strong> <strong id="status-code"></strong></p>
			<pre id="output"></pre>
		</div>
	</div>
</div>

<style>
	.notice, [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
		display: none !important;
	}
</style>
