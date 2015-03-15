/* Config Files for translations */
$.extend(true, $.fn.dataTable.defaults, {
    "language": {
        "lengthMenu": 'Mostrar _MENU_ linhas',
        "zeroRecords": 'Não foram encontrados dados',
        "info": 'Mostrando _START_ até _END_ de _TOTAL_',
        "infoEmpty": "",
        "infoFiltered": "",
        "loadingRecords": "<h3>A carregar dados...</h3>",
        "processing": "<h3>A carregar dados...</h3>",
        "search": "",
        "paginate": {
            "previous": '<i class="fa fa-chevron-left"></i>',
            "next": '<i class="fa fa-chevron-right"></i>'
        }
    },
    "aaSorting": [
        [0, 'asc']
    ],
    "aLengthMenu": [
        [5, 10, 15, 20, -1],
        [5, 10, 15, 20, 'Todos'] // change per page values here
    ],
    "displayLength": 10
});

$.widget("custom.catcomplete", $.ui.autocomplete, {
    _create: function() {
        this._super();
        this.widget().menu("option", "items", "> :not(.ui-autocomplete-category)");
    },
    _renderMenu: function(ul, items) {
        var that = this,
                currentCategory = "";
        $.each(items, function(index, item) {
            var li;
            if (item.category != currentCategory) {
                ul.append("<li class='ui-autocomplete-category'>" + item.category + "</li>");
                currentCategory = item.category;
            }
            li = that._renderItemData(ul, item);
            if (item.category) {
                li.attr("aria-label", item.category + " : " + item.label);
            }
        });
    }
});

$.fn.datepicker.dates['pt'] = {
    days: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado", "Domingo"],
    daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb", "Dom"],
    daysMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa", "Do"],
    months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
    monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
    today: "Hoje",
    clean: "Limpar"
};

var Select = function() {
    var options = function(name) {
        $(name).select2({
            formatNoMatches: function() {
                return "Nenhum resultado encontrado";
            },
            formatInputTooShort: function(input, min) {
                var n = min - input.length;
                return "Introduza " + n + " car" + (n == 1 ? "ácter" : "acteres");
            },
            formatInputTooLong: function(input, max) {
                var n = input.length - max;
                return "Apague " + n + " car" + (n == 1 ? "ácter" : "acteres");
            },
            formatSelectionTooBig: function(limit) {
                return "Só é possível selecionar " + limit + " elemento" + (limit == 1 ? "" : "s");
            },
            formatLoadMore: function(pageNumber) {
                return "A carregar mais resultados…";
            },
            formatSearching: function() {
                return "A pesquisar…";
            },
            placeholder: "",
            allowClear: true
        });
    };

    return {
        init: function(name) {
            options(name);
        }
    };

}();

var TextArea = function() {
    return {
        init: function() {
            $('textarea').inputlimiter({
                remText: 'Apenas %n caracteres%s para usar...',
                remFullText: 'Atingiu o limite máximo de caracteres',
                limitText: 'Apenas é possivel colocar %n caracteres neste campo'
            });
            $("textarea").autosize();

            $(document).on('click', 'button', function() {
                $('textarea').inputlimiter({
                    remText: 'Apenas %n caracteres%s para usar...',
                    remFullText: 'Atingiu o limite máximo de caracteres',
                    limitText: 'Apenas é possivel colocar %n caracteres neste campo'
                });
                $('textarea').autosize();
            });

        }
    }
}();

var DatePicker = function() {
    var options = function(name) {
        $(name).datepicker({
            language: 'pt',
            format: 'dd/mm/yyyy',
            autoclose: true
        });
    };

    return {
        init: function(name) {
            options(name);
        }
    };
}();

var TableData = function() {

    var options = function() {
        $('.dataTables_filter input').addClass("form-control input-sm").attr("placeholder", 'Pesquisar...');
        $('.dataTables_empty').addClass('text-center');
        $('.dataTables_processing').addClass('progress-info z-index');
    }

    return {
        static: function(type) {
            $('.static-table').dataTable();
            options();

        },
        dynamic: function(route, timeout) {
            $.get(route, {}, function(data) {
                console.log(data)
            });

            var table = $('.dynamic-table').dataTable({
                "processing": true,
                "serverside": true,
                "ajax": route,
            });

            options();

            setInterval(function() {
                table.api().ajax.reload();
            }, timeout);

        }
    };
}();

var Modal = function() {
    var clean = function() {
        $('#form_modal_left').empty();
        $('#form_modal_right').empty();
        $('#form_modal_title').empty();
    };

    var ShowForm = function(data) {
        clean();
        $('#form_modal_title').append('<i class="' + data['icon'] + '"></i> ' + data['title']);
        $.each(data['att'], function(key, value) {
            label = $('<p>').appendTo('#form_modal_right');
            $('<b>').text(key + ': ').appendTo(label);
            label.append(value);
        });
        if (data.hasOwnProperty('photo')) {
            div = $('<div>').addClass('thumbnail').css('width', '150px').css('height', '150px').appendTo('#form_modal_left');
            $('<img>').attr('width', 150).attr('height', 150).attr('src', data['photo']).appendTo(div);
        }
        $('#form_modal').modal('show');
    };

    var confirm = function() {
        $('tbody').on("click", ".confirm-btn", function() {
            ConfirmForm($(this));
        });
    };

    var ConfirmForm = function(ele) {
        $('#confirm_modal_title').empty();
        $('#confirm_modal_body').empty();
        $('#confirm_modal_title').text(ele.data('title'));
        $('#confirm_modal_body').text(ele.data('body'));
        $('#confirm_modal_okay').attr('data-url', ele.data('url'));
        $('#confirm_modal_okay').attr('data-method', ele.data('method'));
        $('#confirm_modal_okay').attr('data-remove-id', ele.data('remove-id'));
        $('#confirm_modal_okay').attr('data-values', JSON.stringify(ele.data('values')));
        $('#confirm_modal_okay').attr('data-type', ele.data('type'));
        $('#confirm_modal').modal('show');
    };

    return {
        show: function(data) {
            ShowForm(data);
        },
        confirmListener: function() {
            confirm();

            $(document).on('click', '#confirm_modal_okay', function(e) {
                var ele = $(this);
                e.preventDefault();
                var l = Ladda.create(this);
                l.start();
                if (ele.data('type') == 'async') {
                    $.ajax({
                        url: ele.data('url'),
                        type: ele.data('method'),
                        data: JSON.parse(ele.attr('data-values')),
                        async: true,
                        complete: function(data) {
                            console.log(data['responseText']);
                            var response = eval("(" + data['responseText'] + ")");
                            l.stop();
                            if (response['status'] == "success") {
                                Notification.success(response['title'], response['message']);
                                $('#' + ele.attr('data-remove-id')).remove();
                                $('#confirm_modal').modal('hide');
                            } else if (response['status'] == "error") {
                                Notification.error(response['title'], response['message']);
                                $('#confirm_modal').modal('hide');
                            }
                        }
                    });
                } else if (ele.data('type') == 'sync') {
                    var l = Ladda.create(this);
                    $.ajax({
                        url: ele.data('url'),
                        type: ele.data('method'),
                        async: true,
                        complete: function(data) {
                            l.stop();
                            window.location.href = data['responseText'];
                        }
                    });
                }

            });
        }
    };
}();

var PopOver = function() {
    var makeProfileContent = function(img, title, subtitle) {
        return $('<div class="text-center raleway_10"><img style="width: 75px; height: 75px; margin-bottom:2px; " class="thumbnail img-responsive center-block" src="' + img + '" />' + subtitle + '</div>');

    };

    var profilePopover = function() {
        if ($('.popover-style').length) {
            $('.popover-style').popover({
                html: true,
                content: function() {
                    return makeProfileContent($(event.target).data('image'), $(event.target).data('title'), $(event.target).data('subtitle'));
                },
                title: function() {
                    return '<div class="text-center">' + $(event.target).data('title') + '</div>';
                },
                trigger: 'hover',
                placement: 'top'
            });
        }
    };

    return {
        init: function() {
            profilePopover();
        }
    };
}();

var Notification = function() {
    var options = function() {
        $.extend($.gritter.options, {
            position: 'bottom-left',
            fade_in_speed: 400,
            fade_out_speed: 400,
            time: 3000
        });
    };

    var success = function(title, text) {
        options();
        $.gritter.add({
            title: '<i class="clip-checkmark"></i> ' + title,
            class_name: 'gritter-success',
            text: text
        });
    };

    var error = function(title, text) {
        options();
        $.gritter.add({
            title: '<i class="fa fa-exclamation-triangle"></i> ' + title,
            class_name: 'gritter-error',
            text: text
        });
    };

    var info = function(title, text) {
        options();
        $.gritter.add({
            title: '<i class="fa fa-bullhorn"></i> ' + title,
            class_name: 'gritter-info',
            text: text
        });
    };

    var update = function(route) {
        $.post(route, {}, function(data) {
            console.log(data);

            // Updates the total of notifications in the badge
            var badge = $('#notificationBadge');
            if (data['total'] > 0) {
                if (badge.length) {
                    badge.text(data['total']);
                } else {
                    $('#notificationBadgePlace').append('<span class="badge" id="notificationBadge">' + data['total'] + '</span>');
                }
            } else {
                badge.remove();
            }

            // Makes a Notification for each new notifications that weren't viewed with jquery and updates the notification dropdown
            $('#notificationList').empty();
            $.each(data['data'], function(index, value) {
                if (!value['jquery']) {
                    $('#notificationList').prepend('<li style="background:#efeded;"><a href="' + value['route'] + '"><span class="label ' + value['label'] + '"><i class="' + value['icon'] + '"></i></span><span class="message">' + value['name'] + '</span></a></li>');
                    //Notification.info(value['title'], value['name']);
                } else {
                    $('#notificationList').prepend('<li><a href="' + value['route'] + '"><span class="label ' + value['label'] + '"><i class="' + value['icon'] + '"></i></span><span class="message">' + value['name'] + '</span></a></li>');
                }
            });
        });
    };

    return {
        success: function(title, text) {
            success(title, text);
        },
        error: function(title, text) {
            error(title, text);
        },
        info: function(title, text) {
            info(title, text);
        },
        update: function(route) {
            update(route);
        }

    };
}();

var EasyPie = function() {
    return {
        init: function() {
            if (isIE8 || isIE9) {
                if (!Function.prototype.bind) {
                    Function.prototype.bind = function(oThis) {
                        if (typeof this !== "function") {
                            // closest thing possible to the ECMAScript 5 internal IsCallable function
                            throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable");
                        }
                        var aArgs = Array.prototype.slice.call(arguments, 1),
                                fToBind = this,
                                fNOP = function() {
                                }, fBound = function() {
                            return fToBind.apply(this instanceof fNOP && oThis ? this : oThis, aArgs.concat(Array.prototype.slice.call(arguments)));
                        };
                        fNOP.prototype = this.prototype;
                        fBound.prototype = new fNOP();
                        return fBound;
                    };
                }
            }
            $('.easy-pie-chart .bricky').easyPieChart({
                animate: 1000,
                lineWidth: 3,
                barColor: '#C83A2A',
                size: 70
            });

            $('.easy-pie-chart .info').easyPieChart({
                animate: 1000,
                lineWidth: 3,
                barColor: '#5bc0de',
                size: 70
            });

            $('.easy-pie-chart .yellow').easyPieChart({
                animate: 1000,
                lineWidth: 3,
                barColor: '#FFB848',
                size: 70
            });

            $('.easy-pie-chart .teal').easyPieChart({
                animate: 1000,
                lineWidth: 3,
                barColor: '#569099',
                size: 70
            });

            $('.easy-pie-chart .green').easyPieChart({
                animate: 1000,
                lineWidth: 3,
                barColor: '#3D9400',
                size: 70
            });

            $('.easy-pie-chart .yellow').easyPieChart({
                animate: 1000,
                lineWidth: 3,
                barColor: '#FFB848',
                size: 70
            });

        }
    }
}();

var Plot = function() {

    return {
        init: function(route, params) {
            $.post(route, params, function(data) {
                console.log(data);
                if (data['status'] != "error") {
                    var ctx = document.getElementById("experts-processes").getContext("2d");
                    var myLineChart = new Chart(ctx).Line(data['data'], {
                        responsive: true,
                        animation: true,
                        barValueSpacing: 5,
                        barDatasetSpacing: 1,
                        tooltipFillColor: "rgba(0,0,0,0.8)",
                        multiTooltipTemplate: "<%= value %> <%= datasetLabel %>"
                    });
                } else {
                    Notification.error(data['title'], data['message']);
                }
            });
        }
    };

}();

var Calendar = function() {
    return {
        init: function(events, types, routeStore, routeUpdate, routeDestroy, routeMove) {
            var $modal = $('#event-management');
            $('#event-categories div.event-category').each(function() {
                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                // it doesn't need to have a start or end
                var eventObject = {
                    title: $.trim($(this).text()) // use the element's text as the event title
                };
                // store the Event Object in the DOM element so we can get to it later
                $(this).data('eventObject', eventObject);
                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 999,
                    revert: true, // will cause the event to go back to its
                    revertDuration: 50 //  original position after the drag
                });
            });
            /* initialize the calendar
             -----------------------------------------------------------------*/
            var form = '';
            var calendar = $('#calendar').fullCalendar({
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maior', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Aug', 'Set', 'Out', 'Nov', 'Dez'],
                dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                timeFormat: 'H:mm',
                buttonText: {
                    prev: '<i class="fa fa-chevron-left"></i>',
                    next: '<i class="fa fa-chevron-right"></i>',
                    today: 'Hoje',
                    month: 'Mês',
                    week: 'Semana',
                    day: 'Dia'
                },
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: events,
                editable: true,
                selectable: true,
                selectHelper: true,
                select: function(start, end, allDay) {
                    $modal.modal({
                        backdrop: 'static'
                    });
                    form = $("<form></form>");
                    form.append("<div class='row'></div>");
                    var select = form.find(".row").append("<div class='col-md-6'><div class='form-group'><label class='control-label'>Nome do Evento</label><input class='form-control' placeholder='Inserir nome do evento' type=text name='name'/></div><div class='form-group'><label class='control-label'>Descrição do Evento</label><input class='form-control' placeholder='Inserir descrição do evento' type=text name='description'/></div></div>").append("<div class='col-md-6'><div class='form-group'><label class='control-label'>Prioridade</label><select class='form-control' name='type_id'></select></div></div>").find("select[name='type_id']");
                    $.each(types, function(k, v) {
                        select.append('<option value="' + k + '">' + v + '</option>');
                    });

                    $modal.find('.remove-event').hide().end().find('.save-event').show().end().find('.modal-body').empty().prepend(form).end().find('.save-event').unbind('click').click(function() {
                        form.submit();
                    });
                    $modal.find('form').on('submit', function() {
                        var title = form.find("input[name='name']").val();
                        var description = form.find("input[name='description']").val();
                        var $categoryClass = form.find("select[name='type_id'] option:checked").val();
                        if (title !== null && title !== "") {
                            $.post(routeStore, {"name": title, "description": description, "type_id": $categoryClass, "starts": Date.parse(start), "ends": Date.parse(end)}, function(data) {
                                console.log(data);
                                if (data['status'] == 'success') {
                                    Notification.success(data['title'], data['message']);
                                    calendar.fullCalendar('renderEvent', {
                                        id: data['id'],
                                        title: title,
                                        start: start,
                                        end: end,
                                        allDay: allDay,
                                        className: $categoryClass,
                                        description: description,
                                    }, true);
                                } else if (data['status'] == 'error') {
                                    Notification.error(data['title'], data['message']);
                                }
                            });
                        }
                        $modal.modal('hide');
                        return false;
                    });
                    calendar.fullCalendar('unselect');
                },
                eventClick: function(calEvent, jsEvent, view) {
                    var form = $("<form></form>");
                    form.append("<div class='row'></div>");
                    var select = form.find(".row").append("<input type='hidden' name='id' value='" + calEvent.id + "' /><div class='col-md-6'><div class='form-group'><label class='control-label'>Nome do Evento</label><input class='form-control' value='" + calEvent.title + "' type=text name='name'/></div><div class='form-group'><label class='control-label'>Descrição do Evento</label><input class='form-control' value='" + calEvent.description + "' type=text name='description'/></div></div>").append("<div class='col-md-6'><div class='form-group'><label class='control-label'>Prioridade</label><select class='form-control' name='type_id'></select></div></div>").find("select[name='type_id']");
                    $.each(types, function(k, v) {
                        if (k == calEvent.className) {
                            select.append('<option value="' + k + '" selected>' + v + '</option>');
                        } else {
                            select.append('<option value="' + k + '">' + v + '</option>');

                        }
                    });
                    $modal.modal({
                        backdrop: 'static'
                    });
                    $modal.find('.remove-event').show().end().find('.save-event').show().end().find('.modal-body').empty().prepend(form).end().find('.remove-event').unbind('click').click(function() {
                        $.post(routeDestroy, {"id": form.find("input[name=id]").val()}, function(data) {
                            console.log(data);
                            if (data['status'] == 'success') {
                                Notification.success(data['title'], data['message']);
                                calendar.fullCalendar('removeEvents', function(ev) {
                                    return (ev._id == calEvent._id);
                                });
                                $modal.modal('hide');
                            } else if (data['status'] == 'error') {
                                Notification.error(data['title'], data['message']);
                            }
                        });

                    });
                    $modal.find('.save-event').unbind('click').on('click', function() {
                        form.submit();
                    });
                    $modal.find('form').on('submit', function() {
                        $.post(routeUpdate, {"id": form.find("input[name=id]").val(), "name": form.find("input[name=name]").val(), "description": form.find("input[name=description]").val(), "type_id": form.find("select[name='type_id'] option:checked").val()}, function(data) {
                            console.log(data);
                            if (data['status'] == 'success') {
                                Notification.success(data['title'], data['message']);
                                calEvent.id = form.find("input[name=id]").val();
                                calEvent.title = form.find("input[name=name]").val();
                                calEvent.description = form.find("input[name=description]").val();
                                calEvent.className = form.find("select[name='type_id'] option:checked").val();
                                calendar.fullCalendar('updateEvent', calEvent);
                            } else if (data['status'] == 'error') {
                                Notification.error(data['title'], data['message']);
                            }
                            $modal.modal('hide');
                        });

                        return false;
                    });
                },
                eventDrop: function(calEvent, delta, revertFunc) {
                    $.post(routeMove, {"id": calEvent.id, "starts": Date.parse(calEvent.start), "ends": Date.parse(calEvent.end)}, function(data) {
                        console.log(data);
                        if (data['status'] == 'success') {
                            Notification.success(data['title'], data['message']);
                        } else if (data['status'] == 'error') {
                            Notification.error(data['title'], data['message']);
                        }
                    });
                },
                eventResize: function(calEvent, delta, revertFunc) {
                    $.post(routeMove, {"id": calEvent.id, "starts": Date.parse(calEvent.start), "ends": Date.parse(calEvent.end)}, function(data) {
                        console.log(data);
                        if (data['status'] == 'success') {
                            Notification.success(data['title'], data['message']);
                        } else if (data['status'] == 'error') {
                            Notification.error(data['title'], data['message']);
                        }
                    });
                },
                eventMouseover: function(calEvent, delta, revertFunc) {
                    $(this).tooltip({'title': calEvent.description});
                }

            });
        }
    }
}();





