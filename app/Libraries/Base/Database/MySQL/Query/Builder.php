<?php

namespace App\Libraries\Base\Database\MySQL\Query;

use function sprintf;

class Builder
{
    private string $fromTableName;

    /** @var string[] */
    private array $select = [];

    /** @var string[] */
    private array $join = [];

    /** @var string[] */
    private array $where = [];

    /** @var string[] */
    private array $group = [];

    /** @var string[] */
    private array $having = [];

    /** @var string[] */
    private array $order = [];

    private array $binding = [];

    private ?int $limit = null;

    private ?int $page = null;

    private ?int $offset = null;

    /**
     *
     * @param string $fromTableName
     */
    public function __construct(string $fromTableName)
    {
        $this->fromTableName = $fromTableName;
    }

    /**
     * @param string $tableName
     */
    public static function from(string $tableName): self
    {
        // @phpstan-ignore-next-line
        return new static($tableName);
    }

    /**
     * @param string ...$columns
     */
    public function select(string ...$columns): self
    {
        $this->select = array_merge($this->select, $columns);

        return $this;
    }

    /**
     * @param string $tableName
     * @param string $leftKey
     * @param string $rightKey
     * @param string $operator
     */
    public function join(string $tableName, string $leftKey, string $rightKey, string $operator = '='): self
    {
        $this->join[$tableName] = sprintf('JOIN %s ON %s %s %s', $tableName, $leftKey, $operator, $rightKey);

        return $this;
    }

    /**
     * @param string $tableName
     * @param string $leftKey
     * @param string $rightKey
     * @param string $operator
     */
    public function leftJoin(string $tableName, string $leftKey, string $rightKey, string $operator = '='): self
    {
        $this->join[$tableName] = sprintf('LEFT JOIN %s ON %s %s %s', $tableName, $leftKey, $operator, $rightKey);

        return $this;
    }

    /**
     * @param string $tableName
     * @param string $leftKey
     * @param string $rightKey
     * @param string $operator
     */
    public function rightJoin(string $tableName, string $leftKey, string $rightKey, string $operator = '='): self
    {
        $this->join[$tableName] = sprintf('RIGHT JOIN %s ON %s %s %s', $tableName, $leftKey, $operator, $rightKey);

        return $this;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function buildKeyValue(string $key): string
    {
        return sprintf(':%s', $key);
    }

    /**
     * @param string $key
     * @param string|integer|null $value
     */
    public function bind(string $key, $value = null): self
    {
        $this->binding[$this->buildKeyValue($key)] = $value;

        return $this;
    }

    /**
     * @param string $column
     * @param string $key
     * @param null $value
     * @param string $operator
     */
    public function where(string $column, string $key, $value = null, string $operator = '='): self
    {
        $this->bind($key, $value);

        $this->where[] = sprintf('%s %s %s', $column, $operator, $this->buildKeyValue($key));

        return $this;
    }

    /**
     * @param string $condition
     */
    public function whereRaw(string $condition): self
    {
        $this->where[] = sprintf('%s', $condition);

        return $this;
    }

    /**
     * @param string[] $columns
     *
     */
    public function wheres(array $columns, array $values = [], string $connection = 'AND', string $operator = '='): self
    {
        $where = [];

        foreach ($columns as $key => $column) {
            $this->bind($key, $values[$key]);

            $where[] = sprintf('%s %s %s', $column, $operator, $this->buildKeyValue($key));
        }

        $this->where[] = implode(" $connection ", $where);

        return $this;
    }

    /**
     * @param string $column
     */
    public function whereNull(string $column): self
    {
        $this->where[] = sprintf('%s IS NULL', $column);

        return $this;
    }

    /**
     * @param string $column
     *
     * @return $this
     */
    public function whereNotNull(string $column): self
    {
        $this->where[] = sprintf('%s IS NOT NULL', $column);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     *
     * @return $this
     */
    public function whereNotIn(string $column, array $values = []): self
    {
        $query = [];
        foreach ($values as $key => $value) {
            $this->bind($key, $value);
            $query[] = $this->buildKeyValue($key);
        }

        $this->where[] = sprintf(
            '%s NOT IN (%s)',
            $column,
            implode(', ', $query)
        );

        return $this;
    }

    /**
     * @param string $column
     */
    public function group(string $column): self
    {
        $this->group[$column] = $column;

        return $this;
    }

    /**
     * @param string $column
     */
    public function having(string $column): self
    {
        $this->having[$column] = $column;

        return $this;
    }

    /**
     * @param string $column
     * @param string $direction
     */
    public function order(string $column, string $direction = 'ASC'): self
    {
        $direction = ($direction === 'ASC') ? 'ASC' : 'DESC';

        $this->order[$column] = sprintf('%s %s', $column, $direction);

        return $this;
    }

    /**
     * @param integer $limit
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param integer $page
     */
    public function page(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @param integer $offset
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return string
     */
    public function query(): string
    {
        $pattern = sprintf(
            '
            SELECT %s
            FROM %s
            {join}
            {where}
            {group}
            {having}
            {order}
            {limit} {offset}
            ',
            implode(', ', $this->select),
            $this->fromTableName
        );

        if (count($this->join) > 0) {
            $join = implode(chr(10), $this->join);
            $pattern = str_replace(['{join}'], [$join], $pattern);
        } else {
            $pattern = str_replace(['{join}'], [''], $pattern);
        }

        if (count($this->where) > 0) {
            $where = 'WHERE ' . implode(' AND ', $this->where);
            $pattern = str_replace(['{where}'], [$where], $pattern);
        } else {
            $pattern = str_replace(['{where}'], [''], $pattern);
        }

        if (count($this->group) > 0) {
            $group = 'GROUP BY ' . implode(', ', $this->group);
            $pattern = str_replace(['{group}'], [$group], $pattern);
        } else {
            $pattern = str_replace(['{group}'], [''], $pattern);
        }

        if (count($this->having) > 0) {
            $having = 'HAVING ' . implode(' AND ', $this->having);
            $pattern = str_replace(['{having}'], [$having], $pattern);
        } else {
            $pattern = str_replace(['{having}'], [''], $pattern);
        }

        if (count($this->order) > 0) {
            $order = 'ORDER BY ' . implode(', ', $this->order);
            $pattern = str_replace(['{order}'], [$order], $pattern);
        } else {
            $pattern = str_replace(['{order}'], [''], $pattern);
        }

        if ($this->limit > 0) {
            $limit = 'LIMIT ' . $this->limit;
            $pattern = str_replace(['{limit}'], [$limit], $pattern);
        } else {
            $pattern = str_replace(['{limit}'], [''], $pattern);
        }

        if ($this->limit > 0 && null !== $this->page) {
            $offset = ' OFFSET ' . $this->limit * ($this->page - 1);
            $pattern = str_replace(['{offset}'], [$offset], $pattern);
        } elseif ($this->limit > 0 && null !== $this->offset) {
            $offset = ' OFFSET ' . $this->offset;
            $pattern = str_replace(['{offset}'], [$offset], $pattern);
        } else {
            $pattern = str_replace(['{offset}'], [''], $pattern);
        }

        return $pattern;
    }

    /**
     * @return array
     */
    public function binding(): array
    {
        return $this->binding;
    }
}
