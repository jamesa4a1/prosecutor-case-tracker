<?php

namespace App\Policies;

use App\Models\CaseDocument;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any documents.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the document.
     */
    public function view(User $user, CaseDocument $document): bool
    {
        // Check confidentiality based on case
        if ($document->is_confidential || $document->case->is_confidential) {
            return $user->isAdmin() || 
                   ($user->isProsecutor() && $document->case->prosecutor_id === $user->prosecutor?->id);
        }
        
        return $user->can('view', $document->case);
    }

    /**
     * Determine whether the user can create documents.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can upload documents
    }

    /**
     * Determine whether the user can update the document.
     */
    public function update(User $user, CaseDocument $document): bool
    {
        // Only the uploader or admin can update
        return $user->isAdmin() || $document->uploaded_by === $user->id;
    }

    /**
     * Determine whether the user can delete the document.
     */
    public function delete(User $user, CaseDocument $document): bool
    {
        // Admin can delete any document
        if ($user->isAdmin()) {
            return true;
        }
        
        // Uploader can delete their own documents
        return $document->uploaded_by === $user->id;
    }

    /**
     * Determine whether the user can download the document.
     */
    public function download(User $user, CaseDocument $document): bool
    {
        return $this->view($user, $document);
    }
}
