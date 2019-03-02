package lukaszstaniszewski.service;

import lukaszstaniszewski.util.EmailInterface;
import org.junit.Before;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.mockito.Mock;
import org.mockito.Mockito;
import org.mockito.runners.MockitoJUnitRunner;

@RunWith(MockitoJUnitRunner.class)
public class CreateOrderServiceTest {

    @Mock
    private EmailInterface emailInterface;

    private CreateOrderService createOrderService;

    @Before
    public void setUp() throws Exception  {
        this.createOrderService = new CreateOrderService(this.emailInterface);
    }

    @Test
    public void successCreateOrder() throws Exception {
        Mockito.when(this.emailInterface.send(Mockito.isA(String.class))).thenReturn(true);

        this.createOrderService.createOrder("kontakt@lukaszstaniszewski.pl");

        Mockito.verify(this.emailInterface).send("kontakt@lukaszstaniszewski.pl");
    }

    @Test(expected = Exception.class)
    public void notSendEmail() throws Exception {
        Mockito.when(this.emailInterface.send(Mockito.isA(String.class))).thenReturn(false);

        this.createOrderService.createOrder("kontaktlukaszstaniszewski.pl");

        Mockito.verify(this.emailInterface).send("kontaktlukaszstaniszewski.pl");
    }
}
