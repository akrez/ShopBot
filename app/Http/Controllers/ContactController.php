<?php

namespace App\Http\Controllers;

use App\DTO\ContactDTO;
use App\Services\BlogService;
use App\Services\ContactService;
use App\Support\WebResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(
        protected BlogService $blogService,
        protected ContactService $contactService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blog = $this->blogService->findOrFailActiveBlog();

        return view('contacts.index', [
            'contacts' => $this->contactService->getLatestBlogContactsQuery($blog)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $blog = $this->blogService->findOrFailActiveBlog();

        $response = $this->contactService->store($blog, new ContactDTO(
            $request->contact_type,
            $request->contact_key,
            $request->contact_value,
            $request->contact_link,
            $request->contact_order
        ));

        return new WebResponse($response, route('contacts.index'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $contact = $this->contactService->findOrFailActiveBlogContact($id);

        return view('contacts.edit', [
            'contact' => $contact,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $blog = $this->blogService->findOrFailActiveBlog();
        $contact = $this->contactService->findOrFailActiveBlogContact($id);

        $response = $this->contactService->update($blog, $contact, new ContactDTO(
            $request->contact_type,
            $request->contact_key,
            $request->contact_value,
            $request->contact_link,
            $request->contact_order
        ));

        return new WebResponse($response, route('contacts.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        $blog = $this->blogService->findOrFailActiveBlog();
        $contact = $this->contactService->findOrFailActiveBlogContact($id);

        $response = $this->contactService->destroy($blog, $contact);

        return new WebResponse($response, route('contacts.index'));
    }
}
