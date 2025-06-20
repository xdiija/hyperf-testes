<?php

declare(strict_types=1);

namespace App\Helper;

use Hyperf\Contract\StdoutLoggerInterface;

class ValidationHelper
{
    /** * Array para armazenar mensagens de erro de cada campo validado. */
    public array $errorMessages = [];

    /** * Array para armazenar os campos validados. */
    public array $validatedFields = [];
    protected StdoutLoggerInterface $logger;
    /** * Array com os dados a serem validados. */
    private array $data;
    /** * Campo selecionado para validar dados. */
    private string $currentField;
    /** * Alias usado para mensagem de erro. */
    private ?string $currentAlias;
    /** * Mensagem de erro para exibir ao usuário. * Você pode alterar as mensagens. Use "{field}" para referenciar o nome do campo. */
    private array $responseMessages = [
        'required' => '{field} é obrigatório.',
        'string' => '{field} deve ser uma string',
        'alpha' => '{field} deve conter apenas caracteres alfabéticos.', 'alpha_num' => '{field} deve conter apenas caracteres alfabéticos e números.', 'numeric' => '{field} deve conter apenas números.', 'bool' => '{field} deve conter valores booleanos.', 'email' => '{field} é inválido.', 'max_len' => '{field} é muito longo.', 'min_len' => '{field} é muito curto.', 'max_val' => '{field} é muito alto.', 'min_val' => '{field} é muito baixo.', 'enum' => '{field} é inválido.', 'equals' => '{field} não corresponde.', 'must_contain' => '{field} deve conter {chars}.', 'match' => '{field} é inválido.', 'date' => '{field} formato de data inválido.', 'date_after' => 'A data de {field} deve ser superior à {date}.', 'date_equal_or_after' => 'A data de {field} deve ser igual ou superior à {date}.', 'date_before' => 'A data de {field} deve ser inferior à {date}.', 'date_equal_or_before' => 'A data de {field} deve ser igual ou inferior à {date}.', 'array_of_enum' => 'Todos os valores em {field} devem pertencer à lista permitida.', 'array' => '{field} deve ser um array.', 'array_of_numeric' => 'Todos os valores em {field} devem ser numericos',];
    /** * Verifica se a próxima validação no campo deve ser executada ou não. */
    private bool $next = true;
    public function __construct(array $data, StdoutLoggerInterface $logger,)
    {
        $this->data = $data;
        $this->logger = $logger;
    }
    /** * Função para definir mensagens de resposta de erro personalizadas. */
    public function setResponseMessages(array $messages): void
    {
        foreach ($messages as $key => $val) {
            $this->responseMessages[$key] = $val;
        }
    }
    /** * Define o nome do campo para iniciar a validação. */
    public function field(string $name, ?string $alias = null): self
    {
        $this->currentField = $name;
        $this->next = true;
        $this->currentAlias = $alias;
        $this->validatedFields[$this->currentField] = $this->data[$this->currentField];

        return $this;
    }
    /** * Verfica se um campo required contém valor. */
    public function required(): self
    {
        if (!$this->exists()) {
            $this->addErrorMessage('required');
            $this->next = false;
        }
        return $this;
    }
    /** * Verifica se o dado contém apenas valores Alpha * Parametro ignore é opcional, adicione caracteres a serem ignorados. */
    public function alpha(array $ignore = []): self
    {
        if ($this->next && $this->exists() && !ctype_alpha(str_replace($ignore, '', $this->data[$this->currentField]))) {
            $this->addErrorMessage('alpha');
            $this->next = false;
        }
        return $this;
    }
    /** * Verifica se o dado contém apenas valores Alpha-Numéricos * Parametro ignore é opcional, adicione caracteres a serem ignorados. */
    public function alphaNum(array $ignore = []): self
    {
        if ($this->next && $this->exists() && !ctype_alnum(str_replace($ignore, '', $this->data[$this->currentField]))) {
            $this->addErrorMessage('alpha_num');
            $this->next = false;
        }
        return $this;
    }
    /** * Verifica se é um valor numérico. */
    public function numeric(): self
    {
        if ($this->next && $this->exists() && !is_numeric($this->data[$this->currentField])) {
            $this->addErrorMessage('numeric');
            $this->next = false;
        }
        return $this;
    }
    /** * Verifica se o valor é booleano. */
    public function isBool(): self
    {
        if ($this->next && $this->exists() && !is_bool($this->data[$this->currentField]) && !in_array(strtolower((string) $this->data[$this->currentField]), ['true', 'false', '1', '0'], true)) {
            $this->addErrorMessage('bool');
            $this->next = false;
        }
        return $this;
    }
    /** * Verifica se o valor é um email válido. */
    public function email(): self
    {
        if ($this->next && $this->exists() && !filter_var($this->data[$this->currentField], FILTER_VALIDATE_EMAIL)) {
            $this->addErrorMessage('email');
            $this->next = false;
        }
        return $this;
    }
    /** * Verifica se o tamanho de um valor é maior que o limite. */
    public function maxLen(mixed $size): self
    {
        if ($this->next && $this->exists() && strlen($this->data[$this->currentField]) > $size) {
            $this->addErrorMessage('max_len');
            $this->next = false;
        }
        return $this;
    }
    /** * Verifica se o tamanho de um valor é menor que o limite. */
    public function minLen(mixed $size): self
    {
        if ($this->next && $this->exists() && strlen($this->data[$this->currentField]) < $size) {
            $this->addErrorMessage('min_len');
            $this->next = false;
        }
        return $this;
    }
    /** * Verifica se um valor numerico é maior que o limite. */
    public function maxVal(mixed $val): self
    {
        if ($this->next && $this->exists() && $this->data[$this->currentField] > $val) {
            $this->addErrorMessage('max_val');
            $this->next = false;
        }
        return $this;
    }
    /** * Verifica se um valor numerico é menor que o limite. */
    public function minVal(int $val): self
    {
        if ($this->next && $this->exists() && $this->data[$this->currentField] < $val) {
            $this->addErrorMessage('min_val');
            $this->next = false;
        }
        return $this;
    }
    /** * Verifica se o valor está na lista. */
    public function enum(array $list): self
    {
        if ($this->next && $this->exists() && !in_array($this->data[$this->currentField], $list)) {
            $this->addErrorMessage('enum');
            $this->next = false;
        }
        return $this;
    }
    /** * Verifica se o valor é igual. */
    public function equals(mixed $value): self
    {
        if ($this->next && $this->exists() && !$this->data[$this->currentField] == $value) {
            $this->addErrorMessage('equals');
            $this->next = false;
        }
        return $this;
    }
    /** * Verifica se é uma data com formato valido. */
    public function date(string $format = 'Y-m-d'): self
    {
        if ($this->next && $this->exists()) {
            $dateTime = \DateTime::createFromFormat($format, $this->data[$this->currentField]);
            if (!($dateTime && $dateTime->format($format) == $this->data[$this->currentField])) {
                $this->addErrorMessage('date');
                $this->next = false;
            }
        }
        return $this;
    }
    /** * Verifica se a data é superior a uma determinada data. * O formato da data pode ser especificado, permitindo maior flexibilidade. */
    public function dateAfter(mixed $date, string $format = 'd/m/Y'): self
    {
        if ($this->next && $this->exists()) {
            $currentValue = $this->data[$this->currentField];
            $currentDate = \DateTime::createFromFormat($format, $currentValue);
            $comparisonDate = \DateTime::createFromFormat($format, $date);
            if ($comparisonDate >= $currentDate) {
                $this->addErrorMessage('date_after', ['date' => $date]);
                $this->next = false;
            }
        }
        return $this;
    }
    public function dateEqualOrAfter(mixed $date, string $format = 'd/m/Y'): self
    {
        if ($this->next && $this->exists()) {
            $currentDate = \DateTime::createFromFormat($format, $this->data[$this->currentField]);
            $comparisonDate = \DateTime::createFromFormat($format, $date);
            if ($comparisonDate > $currentDate) {
                $this->addErrorMessage('date_equal_or_after', ['date' => $date]);
                $this->next = false;
            }
        }
        return $this;
    }
    /** * Verifica se a data é anterior a uma determinada data. * O formato da data pode ser especificado, permitindo maior flexibilidade. */
    public function dateBefore(mixed $date, string $format = 'd/m/Y'): self
    {
        if ($this->next && $this->exists()) {
            $currentDate = \DateTime::createFromFormat($format, $this->data[$this->currentField]);
            $comparisonDate = \DateTime::createFromFormat($format, $date);
            if ($comparisonDate <= $currentDate) {
                $this->addErrorMessage('date_before', ['date' => $date]);
                $this->next = false;
            }
        }
        return $this;
    }
    public function dateEqualOrBefore(mixed $date, string $format = 'd/m/Y'): self
    {
        if ($this->next && $this->exists()) {
            $currentDate = \DateTime::createFromFormat($format, $this->data[$this->currentField]);
            $comparisonDate = \DateTime::createFromFormat($format, $date);
            if ($comparisonDate < $currentDate) {
                $this->addErrorMessage('date_equal_or_before', ['date' => $date]);
                $this->next = false;
            }
        }
        return $this;
    }
    /** * Verficia se o dados contém determinados caracteres. */
    public function mustContain(string $chars): self
    {
        if ($this->next && $this->exists() && !preg_match('/[' . $chars . ']/i', $this->data[$this->currentField])) {
            $this->addErrorMessage('must_contain', ['chars' => $chars]);
            $this->next = false;
        }
        return $this;
    }
    /** * Verifica se o valor combina com um determinado padrão. */
    public function match(string $pattern): self
    {
        if ($this->next && $this->exists() && !preg_match($pattern, $this->data[$this->currentField])) {
            $this->addErrorMessage('match');
            $this->next = false;
        }
        return $this;
    }
    /** * Verifica o resultado das validações. */
    public function isValid(): bool
    {
        return count($this->errorMessages) == 0;
    }
    /** * Verifica se todos os valores dentro do array pertencem a uma lista específica. */
    public function arrayOfEnum(array $list): self
    {
        if ($this->next && $this->exists()) {
            $value = $this->data[$this->currentField];
            if (!is_array($value)) {
                $this->addErrorMessage('array');
                $this->next = false;
                return $this;
            }
            foreach ($value as $item) {
                if (!in_array($item, $list, true)) {
                    $this->addErrorMessage('array_of_enum');
                    $this->next = false;
                    break;
                }
            }
        }
        return $this;
    }
    /** * Verifica se todos os valores dentro do array são numéricos. */
    public function arrayOfNumeric(): self
    {
        if ($this->next && $this->exists()) {
            $value = $this->data[$this->currentField];
            if (!is_array($value)) {
                $this->addErrorMessage('array');
                $this->next = false;
                return $this;
            }
            foreach ($value as $item) {
                if (!is_numeric($item)) {
                    $this->addErrorMessage('array_of_numeric');
                    $this->next = false;
                    break;
                }
            }
        }
        return $this;
    }
    /** * Verifica se é uma string. */
    public function isString(): self
    {
        if ($this->next && $this->exists()) {
            $value = $this->data[$this->currentField];
            if (!is_string($value)) {
                $this->addErrorMessage('string');
                $this->next = false;
                return $this;
            }
        }
        return $this;
    }
    /** * Verifica se é um array. */
    public function isArray(): self
    {
        if ($this->next && $this->exists()) {
            $value = $this->data[$this->currentField];
            if (!is_array($value)) {
                $this->addErrorMessage('array');
                $this->next = false;
                return $this;
            }
        }
        return $this;
    }
    /** * Cria e adiciona mensagem de erro após cada validação que falhar. */
    private function addErrorMessage(string $type, array $others = []): void
    {
        $field_name = $this->currentAlias ? $this->currentAlias : $this->currentField;
        $msg = str_replace('{field}', $field_name, $this->responseMessages[$type]);
        foreach ($others as $key => $val) {
            $msg = str_replace('{' . $key . '}', $val, $msg);
        }
        $this->logger->warning("ValidationHelper: {$msg}");
        $this->errorMessages[$this->currentField] = $msg;
    }
    
    /** * Verifica se um valor existe. */
    private function exists(): bool
    {
        if (!array_key_exists($this->currentField, $this->data)) {
            return false;
        }
        $value = $this->data[$this->currentField];
        if ($value === null || $value === '') {
            return false;
        }
        if (is_array($value) && empty($value)) {
            return false;
        }
        return true;
    }
}
