

window.FMainTable = function (arParams){
    this.table_id = arParams.table_id;
    this.sel_class = arParams.selectable_class;
    this.obTable = $('#'+this.table_id);
    this.start_item_selected = false;
    this.lastSelectedRow = null;
    this.trs = null;
    this.mouseButtonPreset = false;
    this.ajaxUrl = arParams.ajaxUrl;

    this.year = this.obTable.data('year');
    this.month = this.obTable.data('month');
    this.departament = arParams.departament;

    this.holidayList = arParams.holidays;

    window['access_mode'] = {};

    // if(!!this.obTable)
    //     this.trs = this.obTable.tBodies[0].getElementsByTagName('tr');

    this.Init();
};

window.FMainTable.prototype.Init = function(){
    var node = document.getElementById(this.table_id);
    node.style.userSelect = node.style.MozUserSelect = node.style.WebkitUserSelect = node.style.KhtmlUserSelect = node.style = 'none';
    node.setAttribute('unSelectable', 'on');

    $('.flipper button').on('click', $.proxy(function(e){
        console.log(this, $(e.target).parents('.flipper'));
        $(e.target).parents('.flipper').addClass('flip')
    }, this));

    $('.tMenuEx .dropdown-item').on('click', $.proxy(function(e){
        console.log(e.target);
        this.actionSet($(e.target).data('hour'));
        $('.tMenuEx').removeClass('show');
        return false;
    }, this));

    $('.tMenu .dropdown-item').on('click', $.proxy(function(e){
        console.log(e.target);
        this.actionSet($(e.target).data('hour'));
        $('.tMenu').removeClass('show');
        return false;
    }, this));


    this.obTable.on('mousedown', $.proxy(function(evnt){
        if(evnt.button === 2){
            //$('#tMenu').dropdown('toggle');
        }
    }, this));

    $('.'+this.sel_class)
        .on('mousemove', $.proxy(function(evnt){
            if(this.mouseButtonPreset){
                if(this.start_item_selected)
                    $(evnt.target).removeClass('mselect');
                else
                    $(evnt.target).addClass('mselect');
            }
        }, this))
        .on('mousedown', $.proxy(function(evnt){
            //if(evnt.button !== 2) {
                //console.log('11111');
                //$('.isday.mselect').removeClass('mselect');
                this.start_item_selected = $(evnt.target).hasClass('mselect');
                if (!this.start_item_selected)
                    $(evnt.target).addClass('mselect');
                this.mouseButtonPreset = true;
            //}
        }, this));
    $(document).on('mouseup', $.proxy(function(evnt){
            //if(evnt.button !== 2) {
                this.start_item_selected = false;
                this.mouseButtonPreset = false;
            //}
        }, this));

    $(document).on('keypress', $.proxy(this.onkey, this));
    $(document).on('keyup', $.proxy(this.onKeyUp, this));

    this._renderHolidays();

    $.ajax({
        url: '/getMonthText/',
        method: "POST",
        async: true,
        dataType: 'json',
        data: {year: this.year, month: this.month},
        success: $.proxy(function (data) {
            if(data.error){
                return;
            }
            $('#textTextOfMonth').val(data.result);
            $('#TextOfMonth p').html(data.result.replace(/(?:\r\n|\r|\n)/g, '<br>'));
        }, this)
    });

    $('#currentDepartament, #currentMonth, #currentYear').on('change', $.proxy(this.changeData, this));

    $('#TextOfMonthSave').on('click', $.proxy(function(){
        this.addText($('#textTextOfMonth').val());
    }, this));

    $('#btnAddTextToMonth').on('click', $.proxy(function(){
        new User().showAdminLoginForm(CSession.sessid, function (res) {
            //console.log('btnAddTextToMonth res:', res);
            if (res.success) {
                //$('#modalTextOfMonth textarea').val($('#TextOfMonth p').text());
                $('#modalTextOfMonth').modal('show');
            }
        });
    }, this));

};

window.FMainTable.prototype.addText = function(value){
    $('.loading').removeClass('stop');
    $.ajax({
        url: this.ajaxUrl+'addtext/',
        method: "POST",
        async: true,
        dataType: 'json',
        data: {sid: CSession.sessid, year: this.year, month: this.month, data: value} ,
        success: $.proxy(function (data) {
            $('.loading').addClass('stop');
            if(data.error){
                alert('Ошибка: ' + data.error);
                return;
            }

            $('#TextOfMonth p').html(data.result.replace(/(?:\r\n|\r|\n)/g, '<br>'));
            $('#modalTextOfMonth').modal('hide');

        }, this)
    });
};
window.FMainTable.prototype.parseValuedata = function(value){
        switch (value.toString().toUpperCase()) {
            case 'V': value = 'В';break;
            case 'B': value = 'Бл';break;
            case 'C': value = 'Бс';break;
            case 'K': value = 'К';break;
            case 'R': value = 'Пр';break;
            case 'P': value = 'Р';break;
            case 'D': value = 'Д';break;
            case 'O': value = 'О';break;
            case 'N': value = 'НБ';break;
            case '0': value = '10';break;
            default:
                break;
        }
        return value;
};

window.FMainTable.prototype.onkey = function(event){
    //console.log(event.keyCode, event.key, event);

    if (
        event.target.tagName.toUpperCase() !== 'INPUT' &&
        event.type === "keypress" &&
        ((event.keyCode >= 48 && event.keyCode <= 57) ||
        event.keyCode === 98  || // b больничный
        event.keyCode === 118 || // v выходной
        event.keyCode === 111 || // o отпуск
        event.keyCode === 107 || // k командировка
        event.keyCode === 114 || // r прогул
        event.keyCode === 112 || // p Отпуск без сохранения зп по уходу за ребенком
        event.keyCode === 99  || // c Бес содержания
        event.keyCode === 100 || // d Декрет
        event.keyCode === 110  // n Отстранен от работы
        )
    ) {
        this.actionSet(event.key.toUpperCase());
        // switch (event.key) {
        //     case '1':
        //     case '2':
        //     case '3':
        //     case '4':
        //     case '5':
        //     case '6':
        //     case '7':
        //     case '8':
        //     case '9':
        //     case '0':
        //         this.actionSet(event.key);
        //         break;
        //     case 'b':
        //         this.actionSet(event.key);
        //         break;
        //     default:
        //         //console.log(event.keyCode);
        // }
    }
};

window.FMainTable.prototype.onKeyUp = function(event){
    if (
        event.type === "keyup" &&
        event.keyCode === 46
    ) {
        switch (event.keyCode) {
            case 46: // delete
                this.actionDelete(event.target);
                break;
            default:
            //console.log(event.keyCode);
        }

    }
};

window.FMainTable.prototype.actionDelete = function(e){
    var rows = $('.isday.mselect');
    var _this = this;

    if(rows.length <= 0)
        return;

    $('.loading').removeClass('stop');
    var _data = [];

    $.each(rows, $.proxy(function(a, node){
        var _worker = $(node).parents('tr').data('worker');
        var _dep = $(node).parents('tr').data('departament');

        _data.push({
            'worker_id': _worker,
            'departament_id': _dep,
            'day': $(node).data('day'),
            year: this.obTable.data('year'),
            month: this.obTable.data('month'),
        });
    }, this));

    //console.log('actionDelete', rows, _data);

    $.ajax({
        url: this.ajaxUrl+'delete/',
        method: "POST",
        async: true,
        dataType: 'json',
        data: {sid: CSession.sessid, year: this.year, month: this.month, dep_id: this.departament.Id, data: JSON.stringify(_data)} ,
        success: $.proxy(function (data) {
            $('.loading').addClass('stop');
            if(!!data.ok){
                $(rows).html('').removeClass('mselect');

                this.fillRowInfo(data);
            }

            if(!data.ok){
                window['access_mode']['dep_' + this.departament.Id] = false;
                new User().showUserLoginForm({sessid: CSession.sessid, param: this.departament}, function (res) {
                    console.log('delete auth result:', res);
                    if (res.success) {
                        _this.actionDelete(e);
                    }
                });

            }
        }, this)
    });

};

window.FMainTable.prototype.actionSet = function(value){
    var rows = $('.isday.mselect');
    var _this = this;

    if(rows.length <= 0)
        return;

    $('.loading').removeClass('stop');
    var _data = [];

    $.each(rows, $.proxy(function(a, node){
        var _worker = $(node).parents('tr').data('worker');
        var _dep = $(node).parents('tr').data('departament');

        _data.push({
            'worker_id': _worker,
            'departament_id': _dep,
            'day': $(node).data('day'),
            year: this.year,
            month: this.month,
        });
    }, this));

    //console.log('actionSet', value, rows, _data);

    $.ajax({
        url: this.ajaxUrl+'set/',
        method: "POST",
        async: true,
        dataType: 'json',
        data: {sid: CSession.sessid, year: this.year, month: this.month, dep_id: this.departament.Id, data: JSON.stringify(_data), 'value': value} ,
        success: $.proxy(function (data) {
            $('.loading').addClass('stop');
            if(!!data.ok){
                $(rows).html(this.parseValuedata(value)).removeClass('mselect');
                this.fillRowInfo(data);
            }

            if(data.error){
                window['access_mode']['dep_' + this.departament.Id] = false;
                new User().showUserLoginForm({sessid: CSession.sessid, param: this.departament}, function (res) {
                    console.log('res:', res);
                    if (res.success) {
                        _this.actionSet(value);
                    }
                });

            }

        }, this)
    });

    //$(rows).html(this.parseValuedata(value)).removeClass('mselect');
};

window.FMainTable.prototype.changeData = function(event){
    $.ajax({
        url: '/setFirstData',
        method: "POST",
        async: true,
        dataType: 'json',
        data: {
            ajax: 'Y',
            currentDepartament: $('#currentDepartament').val(),
            currentMonth: $('#currentMonth').val(),
            currentYear: $('#currentYear').val(),
        },
        success: $.proxy(function (data) {
            if(data && data.success)
                location.reload();
        }, this)
    });
};

window.FMainTable.prototype.fillRowInfo = function(data){
    $.each($(this.obTable).find('tr.worker_data'), function(i, row){
        var current_row_worker = $(row).data('worker');
        var current_row_dep = $(row).data('departament');
        data.result.forEach(function(item, index, arr){
            if(item.worker_id === current_row_worker && item.departament_id === current_row_dep){
                $.each($(row).find('.row_info table tr:last-of-type td'), function(i, info_row){
                    switch (i) {
                        case 0: $(info_row).html(item.working_day); break;
                        case 1: $(info_row).html(item.total_hour); break;
                        case 2: $(info_row).html(item.total_hour_ex_holiday); break;
                        case 3: $(info_row).html(item.total_hour_holiday); break;
                        case 4: $(info_row).html(item.holiday_count); break;
                        default:
                    }
                });
            }
        });
    });
};

window.FMainTable.prototype.RowClick = function (currenttr, lock) {
    if (window.event.ctrlKey) {
        toggleRow(currenttr);
    }

    if (window.event.button === 0) {
        if (!window.event.ctrlKey && !window.event.shiftKey) {
            clearAll();
            toggleRow(currenttr);
        }

        if (window.event.shiftKey) {
            selectRowsBetweenIndexes([lastSelectedRow.rowIndex, currenttr.rowIndex])
        }
    }
};

window.FMainTable.prototype.toggleRow = function (row) {
    row.className = row.className === 'selected' ? '' : 'selected';
    lastSelectedRow = row;
};

window.FMainTable.prototype.selectRowsBetweenIndexes = function (indexes) {
    indexes.sort(function(a, b) {
        return a - b;
    });

    for (var i = indexes[0]; i <= indexes[1]; i++) {
        trs[i-1].className = 'selected';
    }
};

window.FMainTable.prototype.clearAll = function() {
    for (var i = 0; i < trs.length; i++) {
        trs[i].className = '';
    }
};

window.FMainTable.prototype._renderHolidays = function(){
    var _this = this;
    var firstDate = new Date(_this.year, _this.month - 1, 1).getTime();
    var lastDate = new Date(_this.year, _this.month, 0).getTime();
    var monthData = [];

    if(_this.holidayList != null && _this.holidayList.length > 0) {
        //_this.obTable.find('.isday').each(function() {
            //console.log(this);


        for(var i in _this.holidayList) {
            if((_this.holidayList[i].startDate >= firstDate) && (_this.holidayList[i].endDate <= lastDate)) {
                monthData.push(_this.holidayList[i]);
            }
        }

        if(monthData.length > 0) {
            _this.obTable.find('.isday').each(function() {
                var currentDay = $(this).data('day');
                var currentDate = new Date(_this.year, _this.month-1, $(this).data('day')).getTime();

                var dayData = [];


                for(var i in monthData) {
                    if(monthData[i].startDate <= currentDate && monthData[i].endDate >= currentDate) {
                        dayData.push(monthData[i]);
                    }
                }

                if(dayData.length > 0)
                {
                    //_this._renderDataSourceDay($(this), currentDate, dayData);
                    var boxShadow = '';

                    for(var i in dayData)
                    {
                        if(boxShadow != '') {
                            boxShadow += ",";
                        }

                        boxShadow += 'inset 0 -' + (parseInt(i) + 1) * 4 + 'px 0 0 ' + dayData[i].color;
                    }

                    $(this).css('box-shadow', boxShadow);
                }

            });
        }


        //});
    }
};


window.User = function(){
    this.isAdmin = false;
    this.access_mode = {};
};
window.User.prototype.showAdminLoginForm = function(_sid, callback){
    if(window['isAdmin'])
        return callback({success: true});

    if(!document.getElementById('modalDefaultHoliday')) {
        window['modalAdmDlg'] = $(document.createElement('div'));
        modalAdmDlg.addClass('modal fade').attr({
            'id': "modalDefaultHoliday",
            'role': "dialog",
            'aria-labelledby': "mySmallModalLabel",
            'aria-hidden': "true"
        });
        modalAdmDlg.html(
            '<div class="modal-dialog modal-dialog-centered" role="document">' +
            '<div class="modal-content">' +
            '<div class="modal-header">' +
            '<h5 class="modal-title" id="exampleModalCenterTitle">Вход администратора</h5>' +
            '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '</div>' +
            '<div class="modal-body">' +
            'Для текущих изменений требуется авторизация Администратора!' +
            '          <div class="form-group">\n' +
            '            <label for="recipient-name" class="col-form-label">Введите пароль</label>\n' +
            '            <input type="password" class="form-control" id="admPassword" autofocus >\n' +
            '            <div class="invalid-feedback errorText">\n' +
            '             ' +
            '             </div>' +
            '          </div>\n' +
            '</div>' +
            '<div class="modal-footer">' +
            '<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>' +
            '<button type="button" class="btn btn-primary" id="modalAdmEnter">Войти</button>' +
            '</div>' +
            '</div>' +
            '</div>'
        );
        modalAdmDlg.find('#modalAdmEnter').on('click', function () {
            modalAdmDlg.find('.errorText').html('');
            $.ajax({
                url: '/login/auth',
                method: "POST",
                async: true,
                dataType: 'json',
                data: {password: $('#admPassword').val(), sid: _sid},
                success: $.proxy(function (data) {
                    console.log(data);
                    if (!data.success) {
                        modalAdmDlg.find('.errorText').html(data.error).show();
                    } else {
                        modalAdmDlg.find('.errorText').hide();
                        window['isAdmin'] = true;
                        modalAdmDlg.modal('hide');
                    }

                    if (callback) {
                        callback(data);
                    }
                }, this)
            });
            //modalAdmDlg.modal('hide');
        });
        modalAdmDlg.find('#admPassword').on('keyup', function (ev) {
            if(ev.keyCode === 13){
                $("#modalAdmEnter").trigger('click');
            }
        });
    }
    modalAdmDlg.find('.errorText').html('').hide();
    modalAdmDlg.find('#admPassword').val('');
    modalAdmDlg.modal('show');
};
window.User.prototype.showUserLoginForm = function(obj, callback){
    if(window['access_mode'] && window['access_mode']['dep_' + obj.param.Id])
        return callback({success: true});

    if(!window['modalLoginDlg']) {
        window['modalLoginDlg'] = $(document.createElement('div'));
        modalLoginDlg.addClass('modal fade').attr({
            'id': "modalUserLoginDlg",
            'role': "dialog",
            'aria-labelledby': "mySmallModalLabel",
            'aria-hidden': "true"
        });
        modalLoginDlg.html(
            '<div class="modal-dialog modal-dialog-centered" role="document">' +
            '<div class="modal-content">' +
            '<div class="modal-header">' +
            '<h5 class="modal-title" id="exampleModalCenterTitle">Вход '+obj.param.FIONach+'</h5>' +
            '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '</div>' +
            '<div class="modal-body">' +
            'Подтвердите, что Вы - '+obj.param.FIONach+'' +
            '          <div class="form-group">\n' +
            '            <label for="recipient-name" class="col-form-label">Введите пароль</label>\n' +
            '            <input type="password" class="form-control" id="depPassword" autofocus >\n' +
            '            <div class="invalid-feedback" id="errorText">\n' +
            '             ' +
            '             </div>' +
            '          </div>\n' +
            '</div>' +
            '<div class="modal-footer">' +
            '<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>' +
            '<button type="button" class="btn btn-primary" id="btnModalLoginEnter">Войти</button>' +
            '</div>' +
            '</div>' +
            '</div>'
        );
        modalLoginDlg.find('#btnModalLoginEnter').on('click', function () {
            $('#errorText').html('');
            $.ajax({
                url: '/login/userauth',
                method: "POST",
                async: true,
                dataType: 'json',
                data: {password: $('#depPassword').val(), dep_id: obj.param.Id || null, sid: obj.sessid || null},
                success: $.proxy(function (data) {
                    console.log(data);
                    if (!data.success) {
                        window['access_mode']['dep_'+obj.param.Id] = false;
                        $('#errorText').html(data.error).show();
                    } else {
                        $('#errorText').hide();
                        //window['isAdmin'] = true;
                        window['access_mode'] = data.data;
                        modalLoginDlg.modal('hide');
                    }

                    if (callback) {
                        callback(data);
                    }
                }, this)
            });
            //modalAdmDlg.modal('hide');
        });
        modalLoginDlg.find('#depPassword').on('keyup', function (ev) {
            if(ev.keyCode === 13){
                $("#btnModalLoginEnter").trigger('click');
            }
        });
    }
    $('#errorText').html('').hide();
    modalLoginDlg.find('#depPassword').val('');
    modalLoginDlg.modal('show');
};


function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

function deleteCookie(name) {
    setCookie(name, "", {
        expires: -1
    })
}

function setCookie(name, value, options) {
    options = options || {};

    var expires = options.expires;

    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }

    if (options.path == null) {
        options.path = '/';
    }

    value = encodeURIComponent(value);

    var updatedCookie = name + "=" + value;

    for (var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }

    document.cookie = updatedCookie;
}

(function () {
    'use strict';

    $('#dropdownRegionSelect .dropdown-item').on('click', function () {
        console.log($(this).data('id'));
        deleteCookie('departament');
        setCookie('region_id', $(this).data('id'));
        location.reload();
        return false;
    });


    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);

    $('#loginAdmBtn').on('click', function() {new User().showAdminLoginForm(CSession.sessid, function (res) {
        console.log('res:', res);
        if (res.success) {
            location.reload();
        }
    })});

})();