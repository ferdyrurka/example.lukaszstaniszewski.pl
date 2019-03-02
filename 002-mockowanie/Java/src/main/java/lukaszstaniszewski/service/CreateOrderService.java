package lukaszstaniszewski.service;

import lukaszstaniszewski.util.EmailInterface;

/**
 * The type Create order service.
 */
public class CreateOrderService {

    private EmailInterface email;

    /**
     * Instantiates a new Create order service.
     *
     * @param email the email
     */
    public CreateOrderService(EmailInterface email) {
        this.email = email;
    }

    /**
     * Create order.
     *
     * @param emailAddress the email
     * @throws Exception the exception
     */
    public void createOrder(String emailAddress) throws Exception {
        if (!this.email.send(emailAddress)) {
            throw new Exception("Email not send!");
        }

        // Other business logic
    }
}
