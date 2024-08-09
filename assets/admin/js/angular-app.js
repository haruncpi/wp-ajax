/******/ (() => { // webpackBootstrap
/*!*********************************************!*\
  !*** ./assets/admin/src/angular-app/app.js ***!
  \*********************************************/
let myApp = angular.module('ajaxApp', []);
myApp.controller('AppCtrl', function ($scope, $http) {
  $scope.sending = false;
  $scope.savedList = [];
  $scope.model = {};
  function initModel() {
    $scope.model = {
      id: null,
      action: '',
      title: '',
      payload: ''
    };
  }
  initModel();
  let $ = jQuery;
  let savedData = localStorage.getItem('wp_ajax_simulator');
  let ajaxUrl = $('input.ajax_url').val();
  let editor = $('#input');
  let output = $('#output');
  if (savedData) {
    $scope.model.payload = savedData;
  }
  $('#input').keydown(function (e) {
    if ((e.ctrlKey || e.metaKey) && (e.keyCode == 13 || e.keyCode == 10)) {
      $('#ajax').click();
    }
  });
  function textToJSON(text) {
    const jsonObject = {};
    const lines = text.split('\n');
    lines.forEach(line => {
      const [key, value] = line.split(':').map(item => item.trim());
      if (key) {
        jsonObject[key] = isNaN(value) ? value : Number(value);
      }
    });
    return jsonObject;
  }
  let jsonViewerConfig = {
    collapsed: false,
    rootCollapsable: false,
    bigNumbers: false
  };
  $('#ajax').click(function () {
    let input = editor.val();
    let jsonData = textToJSON(input);
    if (input && jsonData) {
      try {
        if (!jsonData.action) {
          output.jsonViewer('action key is required');
          return;
        }
        localStorage.setItem('wp_ajax_simulator', input);
        $.ajax({
          type: 'POST',
          url: ajaxUrl,
          data: jsonData,
          beforeSend: function () {
            $scope.sending = true;
            $scope.$digest();
            $('#status-code').hide();
          },
          success: function (res, textStatus, xhr) {
            $('#status-code').html(xhr.status).removeAttr('style');
            output.jsonViewer(res, jsonViewerConfig);
          },
          error: function (err) {
            $('#status-code').html(err.status).css('background-color', 'red');
            let status = err.status;
            let errorText = err.responseJSON || err.responseText;
            if ('0' === errorText && 400 === status) {
              errorText = {
                data: errorText,
                message: 'No AJAX registered with the name `' + jsonData.action + '`'
              };
            }
            output.jsonViewer(errorText, jsonViewerConfig);
          },
          complete: function () {
            $scope.sending = false;
            $scope.$digest();
            $('#status-code').show();
          }
        });
      } catch (error) {
        console.log(error);
      }
    }
  });
  function toFormData(obj) {
    let formData = new FormData();
    for (let [key, val] of Object.entries(obj)) {
      formData.append(key, val);
    }
    return formData;
  }
  let config = {
    transformRequest: angular.identity,
    headers: {
      'Content-Type': undefined
    }
  };
  $scope.init = function () {
    let formData = new FormData();
    formData.append('action', 'ajax_list');
    $http.post(ajaxUrl, formData, config).success(function (res) {
      $scope.savedList = res.data;
    });
  };
  $scope.init();
  $scope.saving = false;
  $scope.save = function (model) {
    let jsonPayload = textToJSON(model.payload);
    if (!jsonPayload.action) {
      alert('action key is required');
      return;
    }
    model.action = 'ajax_list_save';
    model.title = jsonPayload.action;
    $scope.saving = true;
    $http.post(ajaxUrl, toFormData(model), config).success(function (res) {
      $scope.saving = false;
      if (res.success) {
        $scope.init();
      }
    });
  };
  $scope.toggleSelect = function (row) {
    if ($scope.model.id && $scope.model.id == row.id) {
      initModel();
    } else {
      $scope.model = row;
    }
  };
  $scope.remove = function (row) {
    let data = {
      action: 'ajax_list_remove',
      id: row.id
    };
    $http.post(ajaxUrl, toFormData(data), config).success(function (res) {
      if (res.success) {
        $scope.init();
      }
    });
  };
});
/******/ })()
;
//# sourceMappingURL=angular-app.js.map