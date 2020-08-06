<?php

namespace App\Http\Controllers;
use App\Models\Cita;
use App\Models\Citaspendiente;
use App\Models\Paciente;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Calendar\index;

class secretaryController extends Controller
{
    // See and create patients
    function createPatient(){
        $paciente = Paciente::all();
        return view("createPatient", [
            "pacientes" => $paciente
        ]);
    }
    // Post method for create patient, saving on database
    function createp (Request $request) {

        $request ->validate([
            "fecha" => 'required|date|after:today',
            "apellido" => "required",
            "nombre" => "required",
            "cedula" => "required|unique:pacientes|"
        ]);

        $paciente = new Paciente();
        $paciente ->nombre = $request -> nombre;
        $paciente ->Apellido = $request -> apellido;
        $paciente ->Cedula = $request -> cedula;
        $paciente ->fecha_nacimiento = $request -> fecha;
        $paciente ->TIpo_Sangre = $request -> tiposangre;

        $paciente -> save();
        return redirect("createPatient");
    }
    // edit patient
    function editp ($cedula){
        $paciente = Paciente::findorfail($cedula);
        return view("editp", [
            "paciente" => $paciente,
            "id" => $cedula
        ]);
    }
    // post edited patient
    function editedp (Request $request) {
        $request ->validate([
            "fecha" => 'required|date|after:today',
            "apellido" => "required",
            "nombre" => "required",
            "cedula" => "required|unique:pacientes|"
        ]);


        $paciente = Paciente::find($request->ced);
        $paciente = new Paciente();
        $paciente ->nombre = $request -> nombre;
        $paciente ->Apellido = $request -> apellido;
        $paciente ->Cedula = $request -> cedula;
        $paciente ->fecha_nacimiento = $request -> fecha;
        $paciente ->TIpo_Sangre = $request -> tiposangre;

        $paciente -> update();
        return redirect("createPatient");
    }
    //Delete exist patient
    function deletep ($cedula){
        $paciente = Paciente::findorfail($cedula);
        $paciente ->delete();
        return redirect("/createPatient");
    }
    //Show Date
    function cites(){
        $citas = Citaspendiente::all();
        $paciente = Paciente::all();
        $doctores = DB::select('SELECT * FROM usuarios WHERE TIpoUsuario = ?', [3]);
        return view("/cite", [
            "citas" => $citas,
            "doctores" => $doctores,
            "pacientes" => $paciente
        ]);
    }
    // Create Date
    function createc(Request $request){

        $request ->validate([
            "fecha" => 'required|date|after:today',
        ]);

        $cita = new Cita();
        $cita ->Duracion = $request -> duracion;
        $cita ->Fecha = $request -> fecha;
        $cita ->Hora = $request -> hora;
        $cita ->Paciente_asignado = $request -> paciente;
        $cita ->Doctor_Asigando = $request -> doctor;

        $cita -> save();

        return redirect("/cites");
    }
    //Edit date
    function editc($id){
        $paciente = Paciente::all();
        $doctores = DB::select('SELECT * FROM usuarios WHERE TIpoUsuario = ?', [3]);
        $cita = Cita::findorfail($id);
        return view("editc", [
            "cita" => $cita,
            "doctores" => $doctores,
            "pacientes" => $paciente
        ]);
    }
    // post edited date
    function editedc (Request $request){
        $request ->validate([
            "fecha" => 'required|date|after:today',
        ]);

        $cita = Cita::findorfail($request->id);
        $cita ->Duracion = $request -> duracion;
        $cita ->Fecha = $request -> fecha;
        $cita ->Hora = $request -> hora;
        $cita ->Paciente_asignado = $request -> paciente;
        $cita ->Doctor_Asigando = $request -> doctor;

        $cita -> update();

        return redirect("/cites");
    }
    // delete date
    function deletec ($id){
        $cita = Cita::findorfail($id);
        $cita -> delete();
        return redirect("/cites");
    }
    // Birthdays
    function birthdays(){
        return view("/birthdays");
    }
    // Get a list of posted birthdays
    function getbirthdays(Request $request){
        $birthdays = DB::select('SELECT * FROM pacientes WHERE MONTH(fecha_nacimiento) = ?', [$request ->birthdaymonth]);
        return view("/getbirthdays", [
            "birthdays" => $birthdays
        ]);
    }
    // List of dates that are not payed but with cost set
    function paydates() {
        $dates = DB::select("SELECT * FROM citaspendientes WHERE Costo is not null");
        return view("/paydates", [
            "dates" => $dates
        ]);
    }
    // post for payed date
    function payedd(Request $request){
        $date = Cita::findorfail($request ->id);
        $date ->Costo = null;
        $date ->update();

        return redirect("/paydates");
    }
    // Date Calendar
    function datecalendar (){
        $citas = Citaspendiente::all();
        return view("/datecalendar", [
            "dates" => $citas
        ]);
    }
    //System Report
    function systemreport(){
        $logs = DB::select("SELECT * FROM log_eventos");
        return view("/systemreport", [
            "logs" => $logs
        ]);
    }

}
