<?php


namespace App\Telegram\State\Data;


use App\Telegram\Controller\MyParticipantController;

class ParticipantListDto implements \JsonSerializable
{
    /**
     * @var int
     */
    private $total;
    /**
     * @var int
     */
    private $currentPage;
    /**
     * @var int
     */
    private $limit;

    public function __construct($total = 0, $currentPage = 0, $limit = MyParticipantController::LIMIT)
    {
        $this->total = $total;
        $this->currentPage = $currentPage;
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getTotalPage(): int
    {
        return ceil($this->total / $this->limit) - 1;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'total' => $this->getTotal(),
            'current_page' => $this->getCurrentPage(),
            'limit' => $this->getLimit(),
        ];
    }

    public static function fromArray(array $data)
    {
        if (!isset($data['total'], $data['current_page'], $data['limit'])) {
            throw new \InvalidArgumentException('Data must have keys');
        }

        return new static($data['total'], $data['current_page'], $data['limit']);
    }
}