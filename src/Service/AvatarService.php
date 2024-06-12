<?php
/**
* Avatar service.
*/

namespace App\Service;

use App\Entity\Avatar;
use App\Entity\User;
use App\Repository\AvatarRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AvatarService.
 */
class AvatarService implements AvatarServiceInterface
{
    /**
     * Target directory.
     */
    private string $targetDirectory;

    /**
     * Avatar repository.
     */
    private AvatarRepository $avatarRepository;

    /**
     * File upload service.
     */
    private FileUploadServiceInterface $fileUploadService;

    /**
     * File system service.
     */
    private Filesystem $filesystem;

    /**
     * Constructor.
     *
     * @param string                     $targetDirectory   Target directory
     * @param AvatarRepository           $avatarRepository  Avatar repository
     * @param FileUploadServiceInterface $fileUploadService File upload service
     * @param Filesystem                 $filesystem        Filesystem component
     */
    public function __construct(string $targetDirectory, AvatarRepository $avatarRepository, FileUploadServiceInterface $fileUploadService, Filesystem $filesystem)
    {
        $this->targetDirectory = $targetDirectory;
        $this->avatarRepository = $avatarRepository;
        $this->fileUploadService = $fileUploadService;
        $this->filesystem = $filesystem;
    }

    /**
     * Update avatar.
     *
     * @param UploadedFile $uploadedFile Uploaded file
     * @param Avatar       $avatar       Avatar entity
     * @param User         $user         User entity
     */
    public function update(UploadedFile $uploadedFile, Avatar $avatar, UserInterface $user): void
    {
        $filename = $avatar->getFilename();

        if (null !== $filename) {
            $this->filesystem->remove(
                $this->targetDirectory.'/'.$filename
            );

            $this->create($uploadedFile, $avatar, $user);
        }
    }

    /**
     * Create avatar.
     *
     * @param UploadedFile $uploadedFile Uploaded file
     * @param Avatar       $avatar       Avatar entity
     * @param User         $user         User entity
     */
    public function create(UploadedFile $uploadedFile, Avatar $avatar, UserInterface $user): void
    {
        $avatarFilename = $this->fileUploadService->upload($uploadedFile);

        $avatar->setUser($user);
        $avatar->setFilename($avatarFilename);
        $this->avatarRepository->save($avatar);
    }

    /**
     * Delete avatar.
     *
     * @param Avatar $avatar Avatar entity
     */
    public function delete(Avatar $avatar): void
    {
        $filename = $avatar->getFilename();

        if (null !== $filename) {
            $this->filesystem->remove(
                $this->targetDirectory.'/'.$filename
            );
            $this->avatarRepository->remove($avatar);
        }
    }

    /**
     * Delete avatar by User entity.
     *
     * @param User $user User entity
     */
    public function deleteByUser(User $user): void
    {
        $avatar = $this->avatarRepository->findOneByUser($user);
        $this->delete($avatar);
    }
}
