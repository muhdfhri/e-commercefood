$(document).ready(function(){
    
    (function($) {
        "use strict";

    
    jQuery.validator.addMethod('answercheck', function (value, element) {
        return this.optional(element) || /^\bcat\b$/.test(value)
    }, "type the correct answer -_-");

    // validate contactForm form
    $(function() {
        $('#contactForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                subject: {
                    required: true,
                    minlength: 4
                },
                phone: {
                    required: true,
                    minlength: 9
                },
                email: {
                    required: true,
                    email: true
                },
                message: {
                    required: true,
                    minlength: 20
                }
            },
            messages: {
                name: {
                    required: "Ayo, kamu pasti punya nama, kan?",
                    minlength: "Nama kamu harus memiliki setidaknya 2 karakter"
                },
                subject: {
                    required: "Ayo, kamu pasti punya subjek, kan?",
                    minlength: "Subjek harus memiliki setidaknya 4 karakter"
                },
                number: {
                    required: "Ayo, kamu pasti punya nomor, kan?",
                    minlength: "Nomor harus memiliki setidaknya 9 karakter"
                },
                email: {
                    required: "Tidak ada email, tidak ada pesan"
                },
                message: {
                    required: "Hmm... iya, kamu harus menulis sesuatu untuk mengirim formulir ini.",
                    minlength: "Pesan kamu harus memiliki setidaknya 10 karakter"
                }                
            },
            submitHandler: function(form) {
                $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $(form).ajaxSubmit({
                    type:"POST",
                    data: $(form).serialize(),
                    url: $(form).attr('action'),
                    success: function() {
                        $('#contactForm :input').attr('disabled', 'disabled');
                        $('#contactForm').fadeTo( "slow", 1, function() {
                            $(this).find(':input').attr('disabled', 'disabled');
                            $(this).find('label').css('cursor','default');
                            $('#success').fadeIn()
                            $('.modal').modal('hide');
		                	$('#success').modal('show');
                        })
                    },
                    error: function() {
                        $('#contactForm').fadeTo( "slow", 1, function() {
                            $('#error').fadeIn()
                            $('.modal').modal('hide');
		                	$('#error').modal('show');
                        })
                    }
                })
            }
        })
    })
        
 })(jQuery)
})