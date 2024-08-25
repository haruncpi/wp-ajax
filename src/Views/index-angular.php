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

		<!-- global settings -->
		<div id="ajax-global-settings" style="display: none;">
			<div class="ajax-global-settings-content">
				<p><strong>Pre-request Script</strong></p>
				<textarea ng-model="globalSettings.preRequestScript" rows="8" style="width: 100%;padding: 5px 10px;"></textarea>
				<br>
				<button ng-click="saveGlobalSettings(globalSettings)" type="button" class="button button-primary save-global-settings">Save</button>
			</div>
		</div>
		<!-- end global settings -->
		<div class="display-flex align-items-center gap-10">
			<div class="display-flex align-items-center gap-10" ng-show="pluginInfo.updateAvailable">
				<span style="color: red;" ng-hide="updating">New version available - <strong>v{{pluginInfo.newVersion}}</strong></span>

				<button type="button" ng-hide="updating" class="button button-primary" ng-click="updatePlugin()">{{updating? 'Updating':'Update Now'}}</button>

				<span class="updating-info" ng-show="updating">
					<span class="dashicons dashicons-update"></span>	
					Updating version from <strong>{{pluginInfo.currentVersion}}</strong> to <strong>{{pluginInfo.newVersion}}</strong>
				</span>	
			</div>

			<button ng-click="openGlobalSettings()" type="button" class="button button-default" style="line-height: 22px;"><span class="dashicons dashicons-admin-settings"></span> Global Settings</button>
		</div>
	</div>

	<?php add_thickbox(); ?>
	<div id="param-info-thickbox" style="display:none;">
		<p>You can use key:value format or JSON</p>
		<table class="input-info-table">
			<tr>
				<td>Key:Value</td>
				<td>JSON</td>
			</tr>
			<tr>
				<td>
					action:profile_update
					name:jhon
					email:jhon@example.com
					hobbies[]:drawing
				</td>
				<td>
					{
						"action" : "profile_update",
						"name": "jhon",
						"email": "jhon@example.com",
						"hobbies": ["drawing"]
					}
				</td>
			</tr>
		</table>
	</div>

	<div class="body-wrapper">
		<input type="hidden" class="ajax_url" value="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
		<div class="input-wrapper">

			<div class="ajax-actions">
				<div class="display-flex align-items-center gap-10">
					<strong>
						<a style="color: #777;" href="#TB_inline?width=300&height=220&inlineId=param-info-thickbox" title="Inputs" class="dashicons dashicons-info-outline thickbox"></a> 
					Inputs</strong>
					<select style="width: 150px; display:none" name="contentType" ng-model="model.contentType">
						<option value="{{type}}" ng-repeat="type in contentTypes">{{type}}</option>
					</select>
				</div>
				<div>
					<span class="selected-title" ng-show="model.id"><strong>Selected</strong>: {{model.title}}</span>
					<button ng-disabled="saving || !model.payload.length" ng-click="save(model)" class="button button-default">{{saving?'Saving': model.id ? 'Update':'Save'}}</button>
					<button ng-disabled="sending || !model.payload.length" class="button button-primary" id="ajax">{{sending?'Sending':'Send'}}</button>
				</div>
			</div>

			<div>
				<div class="display-flex" style="justify-content: space-between;">
					<div class="ajax-input-data-types">
						<div 
						ng-click="setActiveInputType(t)"
						ng-class="activeInputType==t?'active':''" 
						ng-repeat="t in inputTypes">{{t}}</div>
					</div>
				</div>
				<textarea id="input" ng-model="model.payload" rows="12"></textarea>
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
