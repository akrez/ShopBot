<?php

namespace App\Services;

use App\DTO\ContactDTO;
use App\Facades\ResponseBuilder;
use App\Models\Blog;
use App\Models\Contact;

class ContactService
{
    public function getLatestBlogContactsQuery(Blog $blog)
    {
        return $blog->contacts()->orderDefault();
    }

    public function findOrFailActiveBlogContact($contactId)
    {
        $blog = resolve(BlogService::class)->findOrFailActiveBlog();
        $contact = $blog->contacts()->where('id', $contactId)->first();
        abort_unless($contact, 404);

        return $contact;
    }

    public function firstContactById(Blog $blog, $id): ?Contact
    {
        if (strlen($id)) {
            return $blog->contacts()->where('id', $id)->first();
        }

        return null;
    }

    public function store(Blog $blog, ContactDTO $contactDTO)
    {
        $validation = $contactDTO->validate();

        if ($validation->errors()->isNotEmpty()) {
            return ResponseBuilder::status(402)->errors($validation->errors()->toArray());
        }

        $contact = $blog->contacts()->create($contactDTO->data());

        if (! $contact) {
            return ResponseBuilder::status(500)->message('Internal Server Error');
        }

        return ResponseBuilder::status(201)->data($contact)->message(__(':name is created successfully', [
            'name' => __('Contact'),
        ]));
    }

    public function update(Blog $blog, Contact $contact, ContactDTO $contactDTO)
    {
        $validation = $contactDTO->validate();

        if ($validation->errors()->isNotEmpty()) {
            return ResponseBuilder::status(402)->errors($validation->errors()->toArray());
        }

        $isSuccessful = $contact->update($contactDTO->data());
        if (! $isSuccessful) {
            return ResponseBuilder::status(500)->message('Internal Server Error');
        }

        return ResponseBuilder::data($contact)->status(200)->message(__(':name is updated successfully', [
            'name' => __('Contact'),
        ]));
    }

    public function destroy(Blog $blog, Contact $contact)
    {
        if (! $contact->delete()) {
            return ResponseBuilder::status(500)->message('Internal Server Error');
        }

        return ResponseBuilder::status(200)->message(__(':name is deleted successfully', [
            'name' => __('Contact'),
        ]));
    }

    public function exportToExcel(Blog $blog)
    {
        $source = [];

        $source[] = [
            __('validation.attributes.id'),
            __('validation.attributes.contact_type'),
            __('validation.attributes.contact_key'),
            __('validation.attributes.contact_value'),
            __('validation.attributes.contact_link'),
            __('validation.attributes.contact_order'),
        ];

        foreach ($this->getLatestBlogContactsQuery($blog)->get() as $contact) {
            $source[] = [
                $contact->id,
                $contact->contact_type->value,
                $contact->contact_key,
                $contact->contact_value,
                $contact->contact_link,
                $contact->contact_order,
            ];
        }

        return $source;
    }

    public function importFromExcel(Blog $blog, array $rows)
    {
        //
        $skipedRow = 0;
        foreach ($rows as $row) {
            if ($skipedRow < 1) {
                $skipedRow++;

                continue;
            }
            //
            $row = ((array) $row) + array_fill(0, 6, null);
            $id = $row[0];
            //
            $contactDTO = new ContactDTO(
                $row[1],
                $row[2],
                $row[3],
                $row[4],
                $row[5]
            );
            //
            $contact = $this->firstContactById($blog, $id);
            //
            if ($contact) {
                $this->update($blog, $contact, $contactDTO);
            } else {
                $this->store($blog, $contactDTO);
            }
        }
    }
}
