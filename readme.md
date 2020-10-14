### Gandalf

Gandalf is a tiny decision maker for your PHP Projects.

#### Installation

Require gandalf with composer :

```
composer require altherius/gandalf
```

#### Getting started

Gandalf uses a system of voters, implement your own voters implementing `Gandalf\Voter`
with your own business logic :

```php
class PostVoter implements \Gandalf\Voter
{
    public function abstains(string $permission, object $object): bool
    {
        if (!$object instanceof Post) {
            return true;        
        }

        return false;
    }

    public function vote(string $permission, object $object): bool
    {
        /* Implement your decision logic here */
        return true;
    }
}
```

Then in your code, you can use the decision manager :

```php
$post = new Post();
$decisionManager = new \Gandalf\DecisionManager();
$decisionManager->decide('edit', $post);
```

#### Strategies

The decision manager can decide according to 3 different strategies :

 - `STRATEGY_AFFIRMATIVE`: If at least one voter responds affirmatively, the decision manager
 will have a positive decision. This is the default behaviour.
 - `STRATEGY_UNANIMOUS`: All voters that do not abstain have to respond affirmatively.
 - `STRATEGY_CONSENSUS`: A majority of voters have to respond affirmatively.
 
You can pass a strategy when you instanciate the `DecisionManager`.