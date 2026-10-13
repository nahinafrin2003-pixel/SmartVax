<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VaccineList;
use App\Models\Event;
use App\Models\areas;
use App\Models\vaccineReq;
use App\Models\Certificate; // Import the new Certificate model
use Carbon\Carbon; // Import Carbon for date manipulation
use App\Models\User;
use App\Models\Feedback;
use App\Models\Contact;
use App\Models\Suggestion;

class AdminController extends Controller
{
    public function citizens()
    {
        // Fetch all users where the usertype is 'user'
        $data = User::where('usertype', 'user')->get();
        return view('admin.citizens', compact('data'));
    }

    public function delete_citizen($id)
    {
        $user = User::find($id);

        if ($user) {
            // Update the email to prevent login, and clear other sensitive data if desired
            $user->email = 'rejected@smartvax.com';
            $user->password = ''; // Invalidate password
            $user->save();
            return redirect()->back()->with('message', 'User has been deactivated successfully.');
        }

        return redirect()->back()->with('error', 'User not found.');
    }
    public function index()
    {
        // Fetch all the data for the statistics cards
        $requestCount = vaccineReq::count();
        $vaccineStock = VaccineList::sum('quantity');
        $contactCount = Contact::count();
        $feedbackCount = Feedback::count();

        // Fetch the list of vaccine requests for the table
        $data = vaccineReq::all();

        // Pass all the data to the view
        return view('admin.index', compact('data', 'requestCount', 'vaccineStock', 'contactCount', 'feedbackCount'));
    }
    public function approve_request($id)
    {
        $requestData = vaccineReq::find($id);
        if (!$requestData) {
            return redirect()->back()->with('error', 'Request not found.');
        }

        // Find a certificate for this user and vaccine, or prepare to create one.
        $certificate = Certificate::firstOrNew(
            ['user_id' => $requestData->user_id, 'vaccine_name' => $requestData->Vaccine]
        );

        // If it's a new certificate, set initial values.
        if (!$certificate->exists) {
            $certificate->my_name = $requestData->Name;
            $certificate->no_of_doses_taken = 0; // Will be incremented to 1
        }
        
        // Auto-reject if 3 doses are already taken
        if ($certificate->no_of_doses_taken >= 3) {
            $certificate->status = 'Completed';
            $certificate->save();
            $requestData->delete();
            return redirect()->back()->with('message', 'User has completed this vaccination. Request auto-rejected.');
        }

        // Increment dose count
        $certificate->no_of_doses_taken += 1;
        $certificate->request_id = $requestData->id; // Log the request ID

        // Update status and next dose date
        if ($certificate->no_of_doses_taken >= 3) {
            $certificate->status = 'Completed';
            $certificate->next_dose_date = null;
        } else {
            $certificate->status = 'In Progress';
            $certificate->next_dose_date = Carbon::now()->addMonth();
        }

        $certificate->save();
        $requestData->delete();

        return redirect()->back()->with('message', 'Request approved successfully.');
    }

    public function reject_request($id)
    {
        $requestData = vaccineReq::find($id);
        if (!$requestData) {
            return redirect()->back()->with('error', 'Request not found.');
        }

        // Find a certificate for this user and vaccine, or prepare to create a new one.
        $certificate = Certificate::firstOrNew(
            ['user_id' => $requestData->user_id, 'vaccine_name' => $requestData->Vaccine]
        );

        // If it's a new certificate, set initial values before saving.
        if (!$certificate->exists) {
            $certificate->my_name = $requestData->Name;
            $certificate->no_of_doses_taken = 0; // Set default dose count
        }

        // Update the certificate with rejection details
        $certificate->status = 'Rejected';
        $certificate->next_dose_date = null;
        $certificate->request_id = $requestData->id;
        $certificate->save();

        $requestData->delete();

        return redirect()->back()->with('message', 'Request has been rejected.');
    }


    // --- YOUR OTHER EXISTING METHODS ---
    public function vaccine_list()
    {
        $data = VaccineList::all();
        return view('admin.vaccine', compact('data'));
    }

    public function add_vaccine(Request $request)
    {
        $vaccine = new VaccineList;

        $vaccine->vaccine_name    = $request->name;
        $vaccine->manufacturer    = $request->manufacturer;
        $vaccine->quantity        = $request->quantity;
        $vaccine->production_date = $request->production_date;
        $vaccine->expiry_date     = $request->expiary_date;

        $vaccine->save();
        toastr()->closeButton()->addSuccess('Vaccine added Successfully.');

        return redirect()->back();
    }
    public function delete_expired($id)
    {
        $data = VaccineList::find($id);
        $data-> delete();
        return redirect()->back();
    }
    public function postEvent()
    {
        $event=Event::all();
        return view('admin.postEvent', compact('event'));
    }
    
    public function up_post(Request $request)
    {
        $data= new Event;
        $data->EventName = $request-> Event_title;
        $data->vanue = $request-> vanue;
        
        $data->date = $request-> event_date;
        $image = $request-> image;
        if($image)
        {
            $imagename=time().'.'.$image->getClientOriginalExtension();
            $request-> image->move('products',$imagename );
            $data-> image = $imagename;
        }
        $data->save();
        return redirect()->back();        

    }
    public function delete_old($id)
    {
        $data = Event::find($id);
        $image_path= public_path('products/'.$data->image);
        if(file_exists($image_path))
        {
            unlink($image_path);
        }
        $data-> delete();
        return redirect()->back();
    }
        public function areas()
    {
        $data = areas:: all();
        return view('admin.area', compact('data'));
    }
        public function add_area(Request $request)
    {
        $area= new areas;

        $area->city = $request->city;
        $area->thana = $request->thana;
        $area->center_name = $request->center_name;

        $area->save();
        return redirect()->back();

    }
    public function view_feedback()
    {
        $feedback = Feedback::all();
        $contacts = Contact::all();
        return view('admin.view_feedback', compact('feedback', 'contacts'));
    }

    public function resolve_feedback($id)
    {
        $feedback = Feedback::find($id);
        if ($feedback) {
            $feedback->status = 'resolved';
            $feedback->save();
        }
        return redirect()->back();
    }
    public function solve_contact($id)
    {
        $contact = Contact::find($id);
        if ($contact) {
            $contact->delete();
            return redirect()->back()->with('message', 'Contact message has been marked as solved and removed.');
        }
        return redirect()->back()->with('error', 'Contact message not found.');
    }
    public function store_suggestion(Request $request)
    {
        $request->validate([
            'suggestion' => 'required|string',
            'feedback_id' => 'required|exists:feedback,id',
        ]);

        $feedback = Feedback::find($request->feedback_id);

        Suggestion::create([
            'user_id' => $feedback->user_id,
            'vaccine_name' => $feedback->vaccine_name,
            'feedback' => $feedback->feedback,
            'suggestion' => $request->suggestion,
        ]);

        return redirect()->back()->with('message', 'Suggestion has been sent to the user.');
    }

}  

