

@extends('layouts.templateFront')

@section('contact')


<section id="contact" class="contact sections">
    <div class="container">
        <div class="row">
            <div class="main_contact whitebackground">
                <div class="head_title text-center">
                    <h2>Contactanos Registandote</h2>
                    <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula.</p>
                </div>
                <div class="contact_content">
                    <div class="col-md-6">
                        <div class="single_left_contact">
                            {{-- <form action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name" placeholder="Nombre" required="">
                                </div>

                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Email" required="">
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control" name="apellido_paterno" placeholder="Apellido Paterno" required="">
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control" name="apellido_materno" placeholder="Apellido Materno" required="">
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone" placeholder="Telefono" required="">
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="user_type" value="patient">
                                </div>

                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="Password" required="">
                                </div>

                                <div class="center-content">
                                    <input type="submit" value="Submit" class="btn btn-default">
                                </div>
                            </form> --}}

                            <form action="{{ route('register') }}" method="POST" class="p-4 p-md-5 border rounded-3 bg-light shadow-sm">
                                @csrf
                                <h2 class="mb-4 text-center">Registro de Paciente</h2>

                                <div class="form-floating mb-3">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nombre" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="El nombre solo puede contener letras">
                                </div>

                                <div class="form-floating mb-3">
                                    <label for="email">Correo electrónico</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                                </div>

                                <div class="form-floating mb-3">
                                    <label for="apellido_paterno">Apellido Paterno</label>
                                    <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" placeholder="Apellido Paterno" required>
                                </div>

                                <div class="form-floating mb-3">
                                    <label for="apellido_materno">Apellido Materno</label>
                                    <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" placeholder="Apellido Materno" required>
                                </div>

                                <div class="form-floating mb-3">
                                    <label for="phone">Teléfono</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Teléfono" pattern="^[76][0-9]{7}$" maxlength="8" required title="El número debe comenzar con 7 o 6 y tener 8 dígitos">
                                </div>

                                <input type="hidden" name="user_type" value="patient">

                                <div class="form-floating mb-4">
                                    <label for="password">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required minlength="5" maxlength="255">
                                </div>

                                <div class="d-grid pt-3">
                                    <button class="btn btn-primary btn-lg" type="submit">
                                        <i class="bi bi-person-plus-fill me-2"></i>Registrarse
                                    </button>
                                </div>

                                <hr class="my-4">
                                <p class="text-center">¿Ya tienes una cuenta? <a href="{{url('/admin/login') }}">Inicia sesión</a></p>
                            </form>



                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="single_right_contact">
                            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</p>

                            <div class="contact_address margin-top-40">
                                <span>1600 Pennsylvania Ave NW, Washington,</span>
                                <span>DC 20500, United States of America.</span>
                                <span class="margin-top-20">T: (202) 456-1111</span>
                                <span>M: (202) 456-1212</span>
                            </div>

                            <div class="contact_socail_bookmark">
                                <a href=""><i class="fa fa-facebook"></i></a>
                                <a href=""><i class="fa fa-twitter"></i></a>
                                <a href=""><i class="fa fa-google"></i></a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><!-- End of Contact Section -->
@endsection