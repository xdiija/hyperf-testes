<?php declare(strict_types=1);

namespace App\Model\User;

use Hyperf\Database\Model\Collection;
use Hyperf\DbConnection\Model\Model;

/**
 * @property $id
 * @property $username
 * @property $email
 * @property $created_at
 * @property $updated_at
 */
class User extends Model
{
    protected ?string $table = 'users';

    private ?int $id = null;

    private ?string $email = null;

    private ?string $username = null;

    private string $password = '';

    private ?string $apiToken = null;

    private ?string $avatar = '';

    private ?string $mimeType = 'image/png';

    private ?string $bio = '';

    private ?Collection $settings;

    public function __construct()
    {
        $settings = new Collection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getUserIdentifier(): string
    {
        return $this->username ?? '';
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getApiToken(): string
    {
        return $this->apiToken ?? '';
    }

    public function setApiToken(string $token): void
    {
        $this->apiToken = $token;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getBio(): string
    {
        return $this->bio ?? '';
    }

    public function setBio(?string $bio): void
    {
        $this->bio = $bio;
    }

    public function getSettings(): Collection{
        return $this->settings;
    }

    public function getSetting(string $key): String{

        if ($this->settings->contains($key)){
            $setting = new Setting();
            $setting = $this->settings->find($key);
            if (null != $setting)
                return $setting->getValue();
        }
        return '';

    }

    public function setSettings(Collection $settings){
        $this->settings = $settings;
    }

    public static function fromArray($array){

        $user = new User();
        $user->id = $array['id'];
        $user->email = $array['email'];
        $user->username = $array['username'];
        $user->avatar = $array['avatar'];
        $user->bio = $array['bio'];
        return $user;

    }

    public static function settingsFromArray($array){

        $collection = new Collection();
        if (null != $array){
            foreach($array as $a){
                $setting = Setting::fromArray($a);
                $collection->add($setting);
            }
        }
        return $collection;

    }

//    /**
//     * @var int
//     */
//    public $keyType = 'int';
//
//    /**
//     * @var bool
//     */
//    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = [
        'id	',
        'username',
        'email',
        'created_at',
        'updated_at'
    ];
}